<?php

declare(strict_types=1);

namespace App\Tests\Util;

class MusementApiDataProvider
{
    public static function getCitiesJsonData(): string
    {
        return json_encode([
            [
                "id" => 57,
                "top" => false,
                "name" => "Amsterdam",
                "code" => "amsterdam",
                "content" => "Amsterdam is Holland’s capital city, a cultural hub and one of the Europe’s favorite travel destinations. It may not be an enormous city but its unique features are enough to attract tourists for day trips and longer holidays.Built under sea level, it is defined a ‘Venice of the North’ due to its many canals – it was a city built in the year 1000, reclaiming the area from advancing waters. Some of the most popular attractions in Amsterdam are the Rijksmuseum with Rembrant's famous painting 'The Night Watch', the Van Gogh Museum, Anne Frank's House, Museum Willet, the Cromhout Houses and many more.",
                "meta_description" => "Book your tickets for museums, tours and attractions in Amsterdam. Discover the Rijksmuseum, sip a beer at the Heineken Experience or take a tour on the canals.",
                "meta_title" => "Things to do in Amsterdam: Museums, tours, and attractions",
                "headline" => "Things to do in Amsterdam",
                "more" => "Young people come to this city for its nightlife and possibly to see the world renown “Coffee Shops”, while art-lovers on the other hand come to enjoy some of the city’s museums and the beautiful architecture. Holland successfully made its very own Renaissance architecture in the 17th century, giving Amsterdam its very own unique atmosphere.",
                "weight" => 20,
                "latitude" => 52.374,
                "longitude" => 4.9,
                "country" => [
                    "id" => 124,
                    "name" => "Netherlands",
                    "iso_code" => "NL",
                ],
                "cover_image_url" => "https://images.musement.com/cover/0002/15/amsterdam_header-114429.jpeg",
                "url" => "https://www.musement.com/us/amsterdam/",
                "activities_count" => 287,
                "time_zone" => "Europe/Amsterdam",
                "list_count" => 1,
                "venue_count" => 23,
                "show_in_popular" => true
            ],
            [
                "id" => 40,
                "top" => true,
                "name" => "Paris",
                "code" => "paris",
                "content" => "Do you really need a reason? The City of Lights means the Eiffel Tower, the Pompidou Center, the Louvre, the Musée d’Orsay, the Arc de Triomphe, Versaille, Montmartre, the Pantheon and Notre Dame. Then there’s the food, the street life, the history . . . Alas, you’re not the only person arriving in Paris – it pays to do some planning. In Paris, the quality of your experience can depend on the kind of ticket you have. Avoiding the crowds is good. Privileged access is good. Expert guides can reveal more than your guidebook could ever tell you. Sometimes, you discover that a nighttime or early-morning visit is quieter and more atmospheric. If you’re going to ‘do’ Paris, do it right. At the same time, remember there’s a whole other city waiting to be explored. In the laid-back districts of Belleville and Ménilmontant, you can tour the distinctive street art and learn more about such Parisian legends as Seth, Clet, Mesnager or Invader. A historical walk could introduce you to a bloodier history of massacres, executions, plague, torture and imprisonment. At the Fragonard Perfume Museum, you can create your own cologne. War enthusiasts, meanwhile, may be tempted to visit the nearby beaches of Normandy and see the D-Day battlefields first hand. The Dôme des Invalides allows the martial-minded to feast on the activities of Napoleon Bonaparte and explore one of the world’s greatest collections of antique arms and armor.",
                "meta_description" => "Discover Paris and book tickets for tours, attractions and museums. Climb the Eiffel Tower, cruise along the Seine river, or go on a guided tour of the Louvre Museum!",
                "meta_title" => "Things to do in Paris: Museums, tours, and attractions",
                "headline" => "Things to do in Paris",
                "more" => "",
                "weight" => 19,
                "latitude" => 48.866,
                "longitude" => 2.355,
                "country" => [
                    "id" => 60,
                    "name" => "France",
                    "iso_code" => "FR"
                ],
                "cover_image_url" => "https://images.musement.com/cover/0002/49/aerial-wide-angle-cityscape-view-of-paris-xxl-jpg_header-148745.jpeg",
                "url" => "https://www.musement.com/us/paris/",
                "activities_count" => 523,
                "time_zone" => "Europe/Paris",
                "list_count" => 1,
                "venue_count" => 46,
                "show_in_popular" => true
            ]
        ]);
    }
}