<?php

// 1 Абстрактный класс + два наследуемых(люди)


abstract class HumanAbstract
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    abstract public function getGreetings(): string;
    abstract public function getMyNameIs(): string;

    public function introduceYourself(): string
    {
        return $this->getGreetings() . '! ' . $this->getMyNameIs() . ' ' . $this->getName() . '.';
    }
}

class RussianHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return 'Привет';
    }

    public function getMyNameIs(): string
    {
        return 'Меня зовут';
    }
}

class EnglishHuman extends HumanAbstract
{
    public function getGreetings(): string
    {
        return 'Hello';
    }

    public function getMyNameIs(): string
    {
        return 'My name is';
    }
}

$russian = new RussianHuman('Иван');
$english = new EnglishHuman('John');

echo $russian->introduceYourself() . "<br>";
echo $english->introduceYourself() . "<br>";

echo str_repeat('-', 50) . "<br>";


// 2 Инкапсуляция(коты)

class Cat
{
    private string $name;
    private string $color;

    public function __construct(string $name, string $color)
    {
        $this->name  = $name;
        $this->color = $color;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function sayHello(): string
    {
        return 'Привет! Я ' . $this->name . '. Я ' . $this->color . ' кот.';
    }
}

$cat = new Cat('Энди Панда', 'рыжий');
echo $cat->sayHello() . "<br>";
// Цвет через геттер:
echo 'Цвет: ' . $cat->getColor() . "<br>";

echo str_repeat('-', 50) . "<br>";



// 3 Интерфейсы


interface CalculateSquare
{
    public function getSquare(): float;
}

class Rectangle implements CalculateSquare
{
    public function __construct(
        private float $width,
        private float $height
    ) {}

    public function getSquare(): float
    {
        return $this->width * $this->height;
    }
}

class Circle implements CalculateSquare
{
    public function __construct(private float $radius) {}

    public function getSquare(): float
    {
        return M_PI * $this->radius ** 2;
    }
}

// Класс, который НЕ реализует интерфейс (для демонстрации)
class Triangle
{
    public function __construct(
        private float $base,
        private float $height
    ) {}
}

function printSquare(object $obj): void
{
    if ($obj instanceof CalculateSquare) {
        echo 'Объект класса ' . get_class($obj) . '. Площадь: ' . $obj->getSquare() . "<br>";
    } else {
        echo 'Объект класса ' . get_class($obj) . ' не реализует интерфейс CalculateSquare.' . "<br>";
    }
}

$rectangle = new Rectangle(5, 10);
$circle    = new Circle(7);
$triangle  = new Triangle(4, 6);

printSquare($rectangle);
printSquare($circle);
printSquare($triangle);


//  4 Наследование 
echo str_repeat('-', 50) . "<br>";

class Lesson
{
    private string $title;
    private string $text;
    private string $homework;

    public function __construct(string $title, string $text, string $homework)
    {
        $this->title    = $title;
        $this->text     = $text;
        $this->homework = $homework;
    }

    public function getTitle(): string    { return $this->title; }
    public function getText(): string     { return $this->text; }
    public function getHomework(): string { return $this->homework; }

    public function setTitle(string $title): void       { $this->title    = $title; }
    public function setText(string $text): void         { $this->text     = $text; }
    public function setHomework(string $hw): void       { $this->homework = $hw; }
}

class PaidLesson extends Lesson
{
    private float $price;

    public function __construct(string $title, string $text, string $homework, float $price)
    {
        parent::__construct($title, $text, $homework);
        $this->price = $price;
    }

    public function getPrice(): float       { return $this->price; }
    public function setPrice(float $price): void { $this->price = $price; }
}

$paidLesson = new PaidLesson(
    'Урок о наследовании в PHP',
    'Лол, кек, чебурек',
    'Ложитесь спать, утро вечера мудренее',
    99.90
);

echo "<pre>";
var_dump($paidLesson);
echo "</pre>";




