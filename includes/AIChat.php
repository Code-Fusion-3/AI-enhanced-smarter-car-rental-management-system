<?php
class AIChat
{
    private $context = [];
    private $currentState = null;
    private $db;
    private $selectedCar = null;

    public function __construct($connection)
    {
        $this->db = $connection;
        $this->initializeCarKeywords();
    }

    private function initializeCarKeywords()
    {
        $sql = "SELECT DISTINCT make, model FROM cars";
        $result = $this->db->query($sql);
        $carKeywords = [];
        while ($car = $result->fetch_assoc()) {
            $carKeywords[] = strtolower($car['make']);
            $carKeywords[] = strtolower($car['model']);
        }
        $this->responses['car_selection']['keywords'] = array_unique($carKeywords);
    }

    private $responses = [
        'greeting' => [
            'keywords' => ['hello', 'hi', 'hey', 'morning', 'afternoon', 'evening'],
            'responses' => [
                "Hello! I'm your AI assistant for car rentals. How can I assist you today?",
                "Hi there! Ready to explore our AI-Enhanced car rental options?",
                "Welcome! Let me help you find the perfect car for your journey."
            ]
        ],
        'rental_process' => [
            'keywords' => ['rent', 'book', 'reserve', 'how to', 'process', 'steps'],
            'responses' => [
                "Here's how to rent with us:\n1. Browse our selection\n2. Pick your dates\n3. Complete quick booking\n4. Get confirmation\nWould you like to start looking at available cars?",
                "Renting is super easy! Just choose your car, select dates, and book online. Want me to show you our current available vehicles?"
            ]
        ],
        'car_selection' => [
            'keywords' => [],
            'responses' => [
                "The {CAR} is a great choice! Here are the details:\n- Make: {MAKE}\n- Model: {MODEL}\n- Year: {YEAR}\n- Daily Rate: {RATE}\n- Features: {FEATURES}\nWould you like to proceed with booking?",
                "Excellent pick! The {CAR} is available. Details:\n- Make: {MAKE}\n- Model: {MODEL}\n- Year: {YEAR}\n- Daily Rate: {RATE}\n- Features: {FEATURES}\nShall we start the booking process?"
            ]
        ],
        'booking_dates' => [
            'keywords' => ['book', 'date', 'when', 'tomorrow', 'next'],
            'responses' => [
                "Perfect! Please select your preferred pickup date and location. We have spots available at:\n- Downtown\n- Airport\n- West Side",
                "Excellent! Let's get your booking set up. Which pickup location works best for you?"
            ]
        ],
        'vehicle_types' => [
            'keywords' => ['suv', 'sedan', 'luxury', 'sports', 'electric', 'hybrid', 'type'],
            'responses' => [
                "We've got everything from economic sedans to luxury SUVs. What type of vehicle interests you? I can show you specific options!",
                "Our fleet includes compact cars, SUVs, luxury vehicles, and eco-friendly options. What's your preference?"
            ]
        ],
        'pricing_dynamic' => [
            'keywords' => ['price', 'pricing', 'cost', 'rate', 'expensive', 'cheap', 'affordable'],
            'responses' => [
                "Our smart pricing system ensures the best rates based on real-time demand. Currently, prices start at $45/day for compact cars. Would you like to see current rates for specific vehicles?",
                "We use dynamic pricing to give you the best deals. Premium vehicles might cost more, but we often have special offers. What's your budget range?"
            ]
        ],
        'insurance' => [
            'keywords' => ['insurance', 'coverage', 'protect', 'damage'],
            'responses' => [
                "We offer comprehensive coverage options including collision damage waiver and liability protection. Would you like details about our insurance packages?",
                "Safety first! Our insurance options cover everything from basic liability to full protection. Shall I explain the coverage levels?"
            ]
        ],
        'special_offers' => [
            'keywords' => ['deal', 'discount', 'offer', 'special', 'promotion'],
            'responses' => [
                "You're in luck! We currently have weekend specials and weekly rental discounts. Want to see our best deals?",
                "Check out our loyalty program and seasonal promotions. I can calculate the best savings for your rental!"
            ]
        ],
        'locations' => [
            'keywords' => ['location', 'pickup', 'where', 'branch', 'office'],
            'responses' => [
                "We have multiple convenient locations across the city. Our main branch is downtown, with airport pickup available 24/7. Need directions?",
                "Choose from any of our locations - downtown, airport, or suburban branches. Where would you prefer to pick up your rental?"
            ]
        ],
        'booking_process' => [
            'keywords' => ['book', 'rent', 'reserve', 'proceed'],
            'responses' => [
                "Great! Please provide your preferred pickup and return dates.",
                "Let's get started. When would you like to pick up and return the car?"
            ]
        ],
        'confirm_booking' => [
            'keywords' => ['confirm', 'yes', 'proceed', 'continue'],
            'responses' => [
                "Your booking is almost complete! Please confirm your details and payment method.",
                "Perfect! Let's finalize your booking. Please review your details and proceed to payment."
            ]
        ],
        // New intent: Payment Methods
        'payment_methods' => [
            'keywords' => ['payment', 'pay', 'payment method', 'methods', 'card', 'credit card', 'debit card', 'mobile money', 'stripe', 'cash'],
            'responses' => [
                "We accept multiple payment methods including credit/debit cards, mobile money, and secure online payments via Stripe.",
                "You can pay for your rental using credit card, debit card, mobile money, or Stripe. Which method would you like to use?",
                "For your convenience, we support card payments and mobile money. Let me know if you need help with a specific method."
            ]
        ],
        // New intent: Mobile Money
        'mobile_money' => [
            'keywords' => ['mobile money', 'mpesa', 'airtel money', 'tigo pesa', 'mtn mobile', 'pay by phone'],
            'responses' => [
                "Yes, we support mobile money payments such as M-Pesa, Airtel Money, Tigo Pesa, and MTN Mobile. You can select this option during checkout.",
                "Mobile money is a fast and secure way to pay. Just choose your provider at payment and follow the instructions. Need help with mobile money?"
            ]
        ],
        // New intent: Support
        'support' => [
            'keywords' => ['support', 'help', 'contact', 'customer service', 'agent', 'problem', 'issue', 'assistance'],
            'responses' => [
                "Our support team is available 24/7! You can reach us via this chat, email, or phone. What do you need help with?",
                "If you have any issues or need assistance, just let me know. I can connect you to a human agent or provide more information."
            ]
        ],
        // New intent: Help
        'help' => [
            'keywords' => ['help', 'how do i', 'how to', 'can you', 'assist', 'guide', 'faq', 'question'],
            'responses' => [
                "I'm here to help! You can ask me about car availability, booking, payments, or anything else related to rentals.",
                "Need help? Try asking about our cars, booking process, payment options, or type 'FAQ' to see common questions."
            ]
        ],
        'default' => [
            'responses' => [
                "I'm here to assist you with car rentals. Could you provide more details about what you're looking for?",
                "Let me help you find the perfect car. What specific information do you need?"
            ]
        ]
    ];

    public function getResponse($query)
    {
        $query = strtolower($query);

        // 1. Check FAQ table for a matching answer (by keywords or question)
        $faqAnswer = $this->getMatchingFaqAnswer($query);
        if ($faqAnswer) {
            return $faqAnswer;
        }

        // Handle follow-up for car selection
        if ($this->currentState === 'car_selected' && strpos($query, 'yes') !== false) {
            $this->currentState = 'booking_process';
            return $this->getRandomResponse($this->responses['booking_process']['responses']);
        }

        // Handle booking process
        if ($this->currentState === 'booking_process' && strpos($query, 'date') !== false) {
            $this->currentState = 'confirm_booking';
            return $this->getRandomResponse($this->responses['confirm_booking']['responses']);
        }

        // Handle "yes" after showing cars
        if ($query === 'yes' && $this->currentState === 'showing_cars') {
            return "Perfect! Which car catches your eye? Just mention the model name and I'll share all the details.";
        }

        // Check for car selection
        foreach ($this->responses['car_selection']['keywords'] as $keyword) {
            if (strpos($query, $keyword) !== false) {
                // Fetch car details from database
                $sql = "SELECT * FROM cars WHERE LOWER(make) LIKE ? OR LOWER(model) LIKE ?";
                $searchTerm = "%$keyword%";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("ss", $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($car = $result->fetch_assoc()) {
                    $response = $this->getRandomResponse($this->responses['car_selection']['responses']);

                    // Replace placeholders with actual car data
                    $carName = $car['make'] . ' ' . $car['model'];
                    $response = str_replace(
                        ['{CAR}', '{MAKE}', '{MODEL}', '{YEAR}', '{RATE}', '{FEATURES}'],
                        [$carName, $car['make'], $car['model'], $car['year'], $car['daily_rate'], $car['features']],
                        $response
                    );

                    $this->selectedCar = $car;
                    $this->currentState = 'car_selected';
                    return $response;
                }
            }
        }

        // Show available cars from database
        if (strpos($query, 'available') !== false || strpos($query, 'car') !== false) {
            $this->currentState = 'showing_cars';
            $sql = "SELECT make, model, daily_rate FROM cars WHERE status = 'available' ORDER BY daily_rate ASC";
            $result = $this->db->query($sql);

            $response = "Here are our available vehicles:\n\n";
            while ($car = $result->fetch_assoc()) {
                $response .= "🚗 {$car['make']} {$car['model']} - \${$car['daily_rate']}/day\n";
            }
            $response .= "\nWhich one interests you? I can share more details about any model.";
            return $response;
        }

        // Default responses remain the same
        foreach ($this->responses as $type => $data) {
            if ($type === 'default')
                continue;
            foreach ($data['keywords'] as $keyword) {
                if (strpos($query, $keyword) !== false) {
                    return $this->getRandomResponse($data['responses']);
                }
            }
        }

        if (strpos($query, 'available') !== false || strpos($query, 'car') !== false) {
            return "I can show you our available cars, specific models, or help you book right away. What would you like to explore?";

        }
        return $this->getRandomResponse($this->responses['default']['responses']);

    }


    private function getRandomResponse($responses)
    {
        return $responses[array_rand($responses)];
    }

    private function getDefaultResponse()
    {
        $responses = [
            "I'd be happy to help you with that. Could you tell me more about what you're looking for?",
            "Let me assist you with your car rental needs. What specific information would you like?",
            "I can help with bookings, pricing, or finding the perfect car. What interests you most?"
        ];
        return $this->getRandomResponse($responses);
    }

    /**
     * Try to find a matching FAQ answer for the user's query.
     * Returns the answer string if found, or null if not.
     */
    private function getMatchingFaqAnswer($query)
    {
        // Search for active FAQs where the question or keywords match the query (simple LIKE match)
        $sql = "SELECT answer FROM faqs WHERE active = 1 AND (LOWER(question) LIKE ? OR LOWER(keywords) LIKE ?) LIMIT 1";
        $like = "%$query%";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['answer'];
        }
        return null;
    }

}