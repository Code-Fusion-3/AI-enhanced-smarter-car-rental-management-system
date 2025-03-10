<?php
class AIChat {
    private $context = [];
    private $currentState = null;
    private $db;
    private $selectedCar = null;
    private $carSelectionKeywords = [];

    public function __construct($connection) {
        $this->db = $connection;
    }



    
    private $responses = [
        'greeting' => [
            'keywords' => ['hello', 'hi', 'hey', 'hy' ,'morning', 'afternoon', 'evening'],
            'responses' => [
                "Hey there! I'm your personal car rental assistant. What can I help you with today?",
                "Welcome! Looking for the perfect car rental? I'm here to help!",
                "Hi! Ready to find your ideal rental car?"
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
            'keywords' => ['toyota', 'camry', 'honda', 'cr-v', 'bmw'],
            'responses' => [
                "The {CAR} is an excellent choice! It features:\n- Automatic transmission\n- 4 doors\n- Fuel efficient\n- GPS navigation\nWould you like to book it for specific dates?",
                "Great pick! The {CAR} is available now. It includes:\n- Full insurance\n- 24/7 roadside assistance\n- Unlimited mileage\nWhen would you like to rent it?"
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
            'keywords' => ['price', 'cost', 'rate', 'expensive', 'cheap', 'affordable'],
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
        ]
    ];
    
    public function getResponse($query) {
        $query = strtolower($query);

        // Handle "yes" after showing cars
        if ($query === 'yes' && $this->currentState === 'showing_cars') {
            return "Perfect! Which car catches your eye? Just mention the model name and I'll share all the details.";
        }

        // Handle car selection from database
        if ($this->currentState === 'showing_cars') {
            $sql = "SELECT * FROM cars WHERE LOWER(make) LIKE ? OR LOWER(model) LIKE ?";
            $searchTerm = "%$query%";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ss", $searchTerm, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($car = $result->fetch_assoc()) {
                $this->currentState = 'booking';
                return "Excellent choice! The {$car['make']} {$car['model']} features:\n
                - Year: {$car['year']}\n
                - Daily Rate: $" . $car['daily_rate'] . "\n
                - Features: {$car['features']}\n
                When would you like to rent it? I can check availability for your dates.";
            }
        }

        // Show available cars from database
        if (strpos($query, 'available') !== false || strpos($query, 'car') !== false) {
            $this->currentState = 'showing_cars';
            $sql = "SELECT make, model, daily_rate FROM cars WHERE status = 'available' ORDER BY daily_rate ASC";
            $result = $this->db->query($sql);
            
            $response = "Here are our available vehicles:\n\n";
            while ($car = $result->fetch_assoc()) {
                $response .= "🚗 {$car['make']} {$car['model']} - \${$car['daily_rate']}/day\n";   }
            $response .= "\nWhich one interests you? I can share more details about any model.";
            return $response;
        }

        // Default responses remain the same
        foreach ($this->responses as $type => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (strpos($query, $keyword) !== false) {
                    return $this->getRandomResponse($data['responses']);
                }
            }
        }

        return "I can show you our available cars, specific models, or help you book right away. What would you like to explore?";
    }

    
// get car selected keywords as name of car
    private function getCarSelectedKeywords($query) {
        $keywords = [];
        $query = strtolower($query);
        foreach ($this->responses as $type => $data) {
            foreach ($data['keywords'] as $keyword) {
                if (strpos($query, $keyword) !== false) {
                    $keywords[] = $keyword;
                }
            }
        }
        return $keywords;
    }
    

    private function getRandomResponse($responses) {
        return $responses[array_rand($responses)];
    }
    
    private function getDefaultResponse() {
        $responses = [
            "I'd be happy to help you with that. Could you tell me more about what you're looking for?",
            "Let me assist you with your car rental needs. What specific information would you like?",
            "I can help with bookings, pricing, or finding the perfect car. What interests you most?"
        ];
        return $this->getRandomResponse($responses);
    }
   

}
