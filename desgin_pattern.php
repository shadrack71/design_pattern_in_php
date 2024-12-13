<?php 



// Singleton Design Pattern



class DatabaseConnection {
    // Static property to hold the single instance
    private static $instance = null;
    private $connection;

    // Private constructor prevents instantiation from outside
    private function __construct() {
        $this->connection = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
    }

    // Static method to return the single instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}

// Usage
// $db1 = DatabaseConnection::getInstance();
// $db2 = DatabaseConnection::getInstance();

// $connect_2 = $db1->getConnection();

// var_dump($connect_2);
// var_dump($db1 === $db2); // Outputs: bool(true)




// Factory Method Design Pattern




// Product interface
interface Notification {
    public function send($message);
}

// Concrete products
class EmailNotification implements Notification {
    public function send($message) {
        echo "Sending Email: $message\n";
    }
}

class SMSNotification implements Notification {
    public function send($message) {
        echo "Sending SMS: $message\n";
    }
}

// Factory class
class NotificationFactory {
    public static function create($type) {
        switch ($type) {
            case 'email':
                return new EmailNotification();
            case 'sms':
                return new SMSNotification();
            default:
                throw new Exception("Notification type not supported.");
        }
    }
}

// // Usage
// $notification = NotificationFactory::create('shas');
// $notification->send('Hello via sms!');



// Facade Design Pattern





// Subsystem 1: Inventory Management
class Inventory {
    public function checkStock($itemId) {
        echo "Checked stock for item $itemId.\n";
        return true; // Assume the item is in stock
    }
}

// Subsystem 2: Payment Processing
class PaymentGateway {
    public function processPayment($amount) {
        echo "Processed payment of $amount.\n";
        return true; // Assume payment was successful
    }
}

// Subsystem 3: Shipping Service
class Shipping {
    public function shipItem($itemId) {
        echo "Item $itemId has been shipped.\n";
        return true;
    }
}

// Facade: Simplified interface for clients
class OrderFacade {
    private $inventory;
    private $paymentGateway;
    private $shipping;

    public function __construct() {
        $this->inventory = new Inventory();
        $this->paymentGateway = new PaymentGateway();
        $this->shipping = new Shipping();
    }

    public function placeOrder($itemId, $amount) {
        // Simplifies the process by coordinating subsystems
        if ($this->inventory->checkStock($itemId)) {
            if ($this->paymentGateway->processPayment($amount)) {
                $this->shipping->shipItem($itemId);
                echo "Order placed successfully for item $itemId.\n";
            } else {
                echo "Payment failed. Order not placed.\n";
            }
        } else {
            echo "Item $itemId is out of stock.\n";
        }
    }
}

// Client code
// $orderFacade = new OrderFacade();
// $orderFacade->placeOrder(123, 250); // Simplified order placement

// Strategy Design Pattern



// Strategy interface

interface PaymentStrategy {
    public function pay($amount);
}

// Concrete strategies
class PayPalPayment implements PaymentStrategy {
    public function pay($amount) {
        echo "Paid $amount using PayPal.\n";
    }
}

class StripePayment implements PaymentStrategy {
    public function pay($amount) {
        echo "Paid $amount using Stripe.\n";
    }
}

// Context class
class PaymentProcessor {
    private $strategy;

    public function setStrategy(PaymentStrategy $strategy) {
        $this->strategy = $strategy;
    }


    public function createStrategy($type) {
        switch ($type) {
            case 'paypal':
                return new PayPalPayment();
            case 'stripe':
                return new StripePayment();
            default:
                throw new Exception("Payment Methods type not supported.");
        }
    }

    public function process($amount) {
        $this->strategy->pay($amount);
    }
}

// Usage
// $processor = new PaymentProcessor();
// $pay_method = $processor->createStrategy('paypal');
// $pay_method2 = $processor->createStrategy('stripe');

// $processor->setStrategy($pay_method);
// $processor->process(100);

// $processor->setStrategy($pay_method2 );
// $processor->process(200);


// Observer Pattern



// Observable subject
class Event {
    private $observers = [];

    public function attach($observer) {
        $this->observers[] = $observer;
    }

    public function notify($data) {
        foreach ($this->observers as $observer) {
            $observer->handle($data);
        }
    }
}

// Observer interface
interface Observer {
    public function handle($data);
}

// Concrete observers
class EmailNotifier implements Observer {
    public function handle($data) {
        echo "Email Notifier received data: $data\n";
    }
}

class LogWriter implements Observer {
    public function handle($data) {
        echo "Log Writer recorded: $data\n";
    }
}

// Usage
// $event = new Event();
// $event->attach(new EmailNotifier());
// $event->attach(new LogWriter());
// $event->notify('New User Registered');



//Builder Pattern

// Product
class Car {
    public $engine;
    public $wheels;
    public $color;

    public function show() {
        echo "Car with engine: $this->engine, wheels: $this->wheels, color: $this->color.\n";
    }
}

// Builder interface
interface CarBuilder {
    public function setEngine($engine);
    public function setWheels($wheels);
    public function setColor($color);
    public function getCar();
}

// Concrete builder
class SportsCarBuilder implements CarBuilder {
    private $car;

    public function __construct() {
        $this->car = new Car();
    }

    public function setEngine($engine) {
        $this->car->engine = $engine;
    }

    public function setWheels($wheels) {
        $this->car->wheels = $wheels;
    }

    public function setColor($color) {
        $this->car->color = $color;
    }

    public function getCar() {
        return $this->car;
    }
}

// Director
class CarDirector {
    public function build(CarBuilder $builder) {
        $builder->setEngine('V8');
        $builder->setWheels(4);
        $builder->setColor('Red');
        return $builder->getCar();
    }
}

// Usage

// $builder = new SportsCarBuilder();
// $director = new CarDirector();
// $car = $director->build($builder);
// $car->show();




//  Adapter Design Pattern



// Target interface (what the client expects)
interface PaymentProcessor_s {
    public function processPayment($amount);
}

// Adaptee (the existing class with a different interface)
class LegacyPaymentSystem {
    public function makePayment($value) {
        echo "Payment of $value processed using Legacy Payment System.\n";
    }
}

// Adapter (converts Adaptee's interface to the Target interface)
class PaymentAdapter implements PaymentProcessor_s {
    private $legacyPayment;

    public function __construct(LegacyPaymentSystem $legacyPayment) {
        $this->legacyPayment = $legacyPayment;
    }

    public function processPayment($amount) {
        // Delegates the call to the legacy system using its method
        $this->legacyPayment->makePayment($amount);
    }
}

// Client code
function processClientPayment(PaymentProcessor_s $processor, $amount) {
    $processor->processPayment($amount);
}

// Usage
// $legacyPaymentSystem = new LegacyPaymentSystem();
// $adapter = new PaymentAdapter($legacyPaymentSystem);

// processClientPayment($adapter, 100); // Outputs: Payment of 100 processed using Legacy Payment System.






 // Decorator Pattern





// Component interface
interface Coffee {
    public function cost();
    public function description();
}

// Concrete component
class SimpleCoffee implements Coffee {
    public function cost() {
        return 5;
    }

    public function description() {
        return 'Simple Coffee';
    }
}

// Decorator base class
class CoffeeDecorator implements Coffee {
    protected $coffee;

    public function __construct(Coffee $coffee) {
        $this->coffee = $coffee;
    }

    public function cost() {
        return $this->coffee->cost();
    }

    public function description() {
        return $this->coffee->description();
    }
}

// Concrete decorators
class MilkDecorator extends CoffeeDecorator {
    public function cost() {
        return $this->coffee->cost() + 2;
    }

    public function description() {
        return $this->coffee->description() . ', Milk';
    }
}

class SugarDecorator extends CoffeeDecorator {
    public function cost() {
        return $this->coffee->cost() + 1;
    }

    public function description() {
        return $this->coffee->description() . ', Sugar';
    }
}

// Usage
$coffee = new SimpleCoffee();
$coffee = new MilkDecorator($coffee);
$coffee = new SugarDecorator($coffee);

echo $coffee->description() . " costs $" . $coffee->cost();








?>
