<?php

namespace App\Services;

use OpenAI\Client;

class AiService
{
    protected Client $client;
    protected string $model;
    protected array $conversationHistory = [];

    public function __construct()
    {
        $this->client = \OpenAI::client(config('services.openai.api_key'));
        $this->model = config('services.openai.model', 'gpt-3.5-turbo');
    }

    /**
     * Get AI response for a user message
     *
     * @param string $userMessage
     * @param string|null $systemPrompt
     * @param array|null $conversationHistory
     * @return string
     */
    public function getResponse(string $userMessage, ?string $systemPrompt = null, ?array $conversationHistory = null): string
    {
        try {
            // Build messages array
            $messages = [];

            // Add system prompt if provided
            if ($systemPrompt) {
                $messages[] = [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ];
            }

            // Add conversation history if provided
            if ($conversationHistory && is_array($conversationHistory)) {
                $messages = array_merge($messages, $conversationHistory);
            }

            // Add current user message
            $messages[] = [
                'role' => 'user',
                'content' => $userMessage,
            ];

            // Call OpenAI API with a slightly higher temperature for personality
            $response = $this->client->chat()->create([
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.9,
                'max_tokens' => 600,
            ]);

            $raw = $response->choices[0]->message->content ?? '';
            return $this->sanitizeResponse($raw);
        } catch (\Exception $e) {
            \Log::error('OpenAI API Error: ' . $e->getMessage());
            // Return a human-like fallback (avoid repetitive apologies)
            return "Thanks — I couldn't reach my helper right now. Could you rephrase the question or try again in a moment? If it's urgent, please email support@ashcol.com and we'll escalate it.";
        }
    }

    /**
     * Get contextualized response for support tickets
     *
     * @param string $userMessage
     * @param string $context
     * @return string
     */
    public function getSupportResponse(string $userMessage, string $context = ''): string
    {
        // More specific persona and instructions so responses are human-like, include brief reasoning
        $persona = "Ash (Ashcol Support)";
        $systemPrompt = "You are {$persona}, a friendly and helpful support chatbot for Ashcol Service Desk. "
            . "Keep replies concise and human — use an empathetic tone, and avoid unnecessary apologies. "
            . "When addressing problems, first give 1-2 short bullets labelled 'Why:' that explain the reasoning or likely cause, then give a clear 'Answer:' with next steps. "
            . "If the user asks for clarification, ask a single concise follow-up question. "
            . "Always include an easy escalation path (email support@ashcol.com) for complex or urgent matters. "
            . ($context ? "Additional context: {$context}." : "");

        $aiReply = $this->getResponse($userMessage, $systemPrompt);

        // If the AI reply is too short or looks like a generic apology, nudge it to be more helpful
        if (empty($aiReply) || stripos($aiReply, 'i apologize') !== false || stripos($aiReply, "i'm sorry") !== false) {
            // Ask AI for a rephrased helpful reply without starting with an apology
            $followUpPrompt = "Please rephrase the previous reply as {\"Answer\": \"<short actionable answer>\", \"Why\": \"<short reasoning>\"} without leading apologies. Return only the rephrased text.";
            $rephrased = $this->getResponse($userMessage . "\n\nRephrase: " . $followUpPrompt, $systemPrompt);
            if (!empty($rephrased)) {
                return $this->sanitizeResponse($rephrased);
            }
        }

        return $aiReply;
    }

    /**
     * Sanitize and tweak AI responses to sound more human and avoid repetitive apologies.
     *
     * @param string $text
     * @return string
     */
    private function sanitizeResponse(string $text): string
    {
        $result = trim($text);

        // Remove excessive leading apologies
        $result = preg_replace('/^(I\s+apologize[\.,:]?\s*)/i', '', $result);
        $result = preg_replace('/^(I\'m\s+sorry[\.,:]?\s*)/i', '', $result);

        // Replace remaining 'I apologize' patterns in-line with friendlier phrasing
        $result = preg_replace('/I\s+apologize/i', 'Thanks for letting me know', $result);

        // Ensure there's a friendly closing if none exists
        if (!preg_match('/(thank you|thanks|cheers|regards|best|hope that helps)/i', $result)) {
            $result = rtrim($result, ".!") . ".";
            $result .= "\n\nIf you'd like, I can help further — just tell me more or type 'help'.";
        }

        return $result;
    }

    /**
     * Summarize text using AI
     *
     * @param string $text
     * @param int $maxLength
     * @return string
     */
    public function summarize(string $text, int $maxLength = 150): string
    {
        $systemPrompt = "Summarize the following text in {$maxLength} characters or less, keeping it clear and concise.";

        return $this->getResponse($text, $systemPrompt);
    }

    /**
     * Classify user intent from message
     *
     * @param string $message
     * @return string
     */
    public function classifyIntent(string $message): string
    {
        $systemPrompt = "Classify the user's intent in one word from these categories: "
            . "greeting, ticket_inquiry, account_issue, service_info, complaint, general_question, or other. "
            . "Respond with only the category name.";

        return $this->getResponse($message, $systemPrompt);
    }

    /**
     * Extract entities from user message (like email, phone, ticket number)
     *
     * @param string $message
     * @return array
     */
    public function extractEntities(string $message): array
    {
        $systemPrompt = "Extract any relevant entities from the user message. "
            . "Look for: email addresses, phone numbers, ticket numbers, names. "
            . "Return as JSON with keys: emails, phone_numbers, ticket_numbers, names. "
            . "If not found, use empty arrays. Return only valid JSON.";

        $response = $this->getResponse($message, $systemPrompt);

        try {
            return json_decode($response, true) ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Generate a ticket description from user message
     *
     * @param string $userMessage
     * @return string
     */
    public function generateTicketDescription(string $userMessage): string
    {
        $systemPrompt = "Create a professional ticket description from the user's message. "
            . "Make it clear, concise, and actionable. Keep it under 500 characters. "
            . "Return only the description, no explanations.";

        return $this->getResponse($userMessage, $systemPrompt);
    }

    /**
     * Check if message contains urgent keywords
     *
     * @param string $message
     * @return bool
     */
    public function isUrgent(string $message): bool
    {
        $systemPrompt = "Is this message urgent or critical? Answer with only 'true' or 'false'.";
        $response = $this->getResponse($message, $systemPrompt);

        return strtolower(trim($response)) === 'true';
    }
}
