<?php

function get_fruit_image_src_by_week($week) {
    $fruit_map = [
        1 => 'seed.jpg', 2 => 'seed.jpg', 3 => 'seed.jpg', 4 => 'seed.jpg', // Early weeks
        5 => 'apple.jpg', 6 => 'sweet-pea.jpg', 7 => 'blueberry.jpg', 8 => 'raspberry.jpg',
        9 => 'cherry.jpg', 10 => 'prune.jpg', 11 => 'lime.jpg', 12 => 'plum.jpg',
        13 => 'peach.jpg', 14 => 'lemon.jpg', 15 => 'orange.jpg', 16 => 'avocado.jpg',
        17 => 'pear.jpg', 18 => 'bell-pepper.jpg', 19 => 'mango.jpg', 20 => 'banana.jpg',
        21 => 'pomegranate.jpg', 22 => 'papaya.jpg', 23 => 'grapefruit.jpg', 24 => 'corn.jpg',
        25 => 'cauliflower.jpg', 26 => 'lettuce.jpg', 27 => 'cabbage.jpg', 28 => 'eggplant.jpg',
        29 => 'squash.jpg', 30 => 'cucumber.jpg', 31 => 'coconut.jpg', 32 => 'jicama.jpg',
        33 => 'pineapple.jpg', 34 => 'cantaloupe.jpg', 35 => 'honeydew.jpg', 36 => 'romaine-lettuce.jpg',
        37 => 'swiss-chard.jpg', 38 => 'leek.jpg', 39 => 'watermelon.jpg', 40 => 'pumpkin.jpg'
    ];

    $image_name = isset($fruit_map[$week]) ? $fruit_map[$week] : 'default.jpg'; // default.jpg as a fallback
    return 'images/' . $image_name;
}

?>