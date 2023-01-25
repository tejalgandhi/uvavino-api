<?php

namespace App\Providers;

use Faker\Provider\Base;

class FakerServiceProvider extends Base
{
    public $animal_names = ['Alecrim', 'Alex', 'Alice', 'Amarelinho', 'Ambar', 'Amendoim', 'Archie', 'Arcol', 'Artemisa', 'Ary', 'Arya', 'Atena', 'Aurora', 'Babalu', 'Barbudo', 'Bea', 'Biscoito', 'Chico', 'Cinza', 'Codi', 'Cookie', 'Denver', 'Dona Xica', 'Elefante', 'Estrela', 'Felicia', 'Flor', 'Florbela', 'Francisca', 'Fuji', 'Gaspar', 'Gaya', 'Gil', 'Jade', 'Jecky', 'Johnny', 'Joy', 'Kat', 'Kedi', 'Kid', 'Kit', 'Kyra', 'Leggy', 'Leica', 'Lia', 'Luana', 'Luca', 'Lucky', 'Luna', 'Mamy', 'Matias', 'Melba', 'Miona', 'Neruda', 'Nikita', 'Oliver', 'Oscar', 'Pakora', 'Panti', 'Pança', 'Ping', 'Pirata', 'Plaza', 'Pong', 'Porto', 'Queen', 'Ravi', 'Robin', 'Rufi', 'Sancho', 'Simba', 'Sophie', 'Tareka', 'Tita', 'Trevo', 'Tricolor', 'Vasco', 'Yoko', 'Zenit', 'Óscar'];
    public $tags = ['javascript', 'java', 'python', 'c#', 'php', 'android', 'html', 'jquery', 'c++', 'css', 'ios', 'mysql', 'sql', 'r', 'asp.net', 'node.js', 'arrays', 'c', 'ruby-on-rails', 'json', '.net', 'sql-server', 'objective-c', 'swift', 'angularjs', 'python-3.x', 'reactjs', 'django', 'excel', 'regex', 'angular', 'iphone', 'ruby', 'ajax', 'xml', 'linux', 'asp.net-mvc', 'vba', 'spring', 'database', 'pandas', 'wordpress', 'wpf', 'laravel', 'string', 'xcode', 'windows', 'mongodb', 'vb.net', 'bash', 'typescript', 'oracle', 'git', 'multithreading', 'postgresql', 'eclipse', 'list', 'forms', 'algorithm', 'macos', 'amazon-web-services', 'image', 'scala', 'twitter-bootstrap', 'firebase', 'visual-studio', 'python-2.7', 'azure', 'spring-boot', 'winforms', 'performance', 'matlab', 'apache', 'function', 'entity-framework', 'react-native', 'powershell', 'facebook', 'hibernate', 'sqlite', 'docker', 'api', 'numpy', 'rest', 'linq', 'shell', 'selenium', 'dataframe', 'qt', 'swing', 'maven', 'loops', 'csv', 'unit-testing', 'file', 'android-studio', 'express', '.htaccess', 'class', 'codeigniter'];
    public $categories = ['Academia', 'Anime', 'Artificial Intelligence', 'Arts & Crafts', 'Astronomy', 'Aviation', 'Bicycles', 'Bioinformatics', 'Biology', 'Board & Card Games', 'Chemistry', 'Chess', 'Coffee', 'Community Building', 'Computer Graphics', 'Computer Science', 'Cryptography', 'Data Science', 'Earth Science', 'Ebooks', 'Economics', 'Engineering', 'Freelancing', 'Gardening & Landscaping', 'Geography', 'Graphic Design', 'History', 'Home Improvement', 'Information Security', 'Internet of Things', 'Interpersonal Skills', 'Law', 'Lifehacks', 'Linguistics', 'Literature', 'Martial Arts', 'Mathematics', 'Medical Sciences', 'Movies & TV', 'Music', 'Mythology & Folklore', 'Neuroscience', 'Open Source', 'Operations Research', 'Parenting', 'Personal Finance & Money', 'Pets', 'Philosophy', 'Photography', 'Physical Fitness', 'Physics', 'Politics', 'Psychology', 'Puzzling', 'Quantum Computing', 'Radio', 'Raspberry Pi', 'Robotics', 'Science Fiction', 'Skeptics', 'Sound Design', 'Space Exploration', 'Sports', 'Stellar', 'Travel', 'Tridion', 'User Experience', 'Veganism & Vegetarianism', 'Video Production', 'Writing'];

    public function animal()
    {
        return $this->animal_names[rand(0, count($this->animal_names) - 1)];
    }

    public function tag()
    {
        return $this->tags[rand(0, count($this->tags) - 1)];
    }

    public function category()
    {
        return $this->categories[rand(0, count($this->categories) - 1)];
    }
}
