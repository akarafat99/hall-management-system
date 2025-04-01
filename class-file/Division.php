<?php

/**
 * Get all divisions of Bangladesh with their districts' distances from Jashore (in km).
 *
 * Note: The following distances are approximate and for demonstration purposes only.
 */
function getDivisions() {
    $divisions = array(
        "Barishal" => array(
            "Barguna"    => 220,
            "Barishal"  => 230,
            "Bhola"     => 240,
            "Jhalakathi"=> 210,
            "Patuakhali"=> 250,
            "Pirojpur"  => 215
        ),
        "Chattogram" => array(
            "Bandarban"      => 380,
            "Brahmanbaria"   => 360,
            "Chandpur"       => 340,
            "Chattogram"     => 370,
            "Cumilla"        => 330,
            "Cox’s Bazar"    => 400,
            "Feni"           => 350,
            "Khagrachhari"   => 420,
            "Lakshmipur"     => 320,
            "Noakhali"       => 310,
            "Rangamati"      => 390
        ),
        "Dhaka" => array(
            "Dhaka"         => 200,
            "Faridpur"      => 180,
            "Gazipur"       => 190,
            "Gopalganj"     => 210,
            "Kishoreganj"   => 220,
            "Madaripur"     => 230,
            "Manikganj"     => 190,
            "Munshiganj"    => 200,
            "Narayanganj"   => 210,
            "Narsingdi"     => 220,
            "Rajbari"       => 180,
            "Shariyatpur"   => 160,
            "Tangail"       => 150
        ),
        "Khulna" => array(
            "Bagerhat"  => 70,
            "Chuadanga" => 80,
            "Jashore"   => 0,   // Reference point: distance from Jashore to itself
            "Jhenaidah" => 50,
            "Khulna"    => 100,
            "Kushtia"   => 40,
            "Magura"    => 30,
            "Meherpur"  => 20,
            "Narail"    => 40,
            "Satkhira"  => 90
        ),
        "Rajshahi" => array(
            "Bogura"            => 250,
            "Chapai Nawabganj"  => 230,
            "Joypurhat"         => 240,
            "Naogaon"           => 260,
            "Natore"            => 240,
            "Pabna"             => 220,
            "Rajshahi"          => 210,
            "Sirajganj"         => 230
        ),
        "Rangpur" => array(
            "Dinajpur"    => 300,
            "Gaibandha"   => 320,
            "Kurigram"    => 310,
            "Lalmonirhat" => 330,
            "Nilphamari"  => 340,
            "Panchagarh"  => 350,
            "Rangpur"     => 360,
            "Thakurgaon"  => 370
        ),
        "Mymensingh" => array(
            "Jamalpur"    => 180,
            "Mymensingh"  => 190,
            "Netrokona"   => 200,
            "Sherpur"     => 210
        ),
        "Sylhet" => array(
            "Habiganj"    => 250,
            "Moulvibazar" => 260,
            "Sunamganj"   => 270,
            "Sylhet"      => 280
        )
    );
    
    return $divisions;
}

?>

<!-- end -->