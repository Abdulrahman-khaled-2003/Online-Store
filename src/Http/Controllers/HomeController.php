<?php

namespace App\Http\Controllers;

class HomeController
{
    public function index()
    {
        view("home", [
            "categories" => $this->getCategories()
        ]);
        die();
    }

    private function getCategories()
    {
        $categories = [
            [
                "id" => 1,
                "categoryName" =>  "Clothies Category",
                "Products" => [
                    [
                        "ProductName" => "blue T-shirt",
                        "ProductImage" => "https://pangaia.com/cdn/shop/files/DNA_Oversized_T-Shirt_-Summit_Blue-1.png?v=1707830032",
                        "ProductPrice" => 50
                    ],
                    [
                        "ProductName" => "White T-shirt",
                        "ProductImage" => "https://www.houseofblanks.com/cdn/shop/files/HeavyweightTshirt_White_01_2.jpg?v=1726516822&width=1946",
                        "ProductPrice" => 60
                    ],
                    [
                        "ProductName" => "Jeans",
                        "ProductImage" => "https://img-lcwaikiki.mncdn.com/mnpadding/1020/1360/ffffff/pim/productimages/20251/7566377/v1/l_20251-s51910z8-h45_u.jpg",
                        "ProductPrice" => 100
                    ],
                    [
                        "ProductName" => "open green T-shirt",
                        "ProductImage" => "https://nobero.com/cdn/shop/files/aloe-green_ee108460-95d3-44ff-b4a0-cf0880393cf3.jpg?v=1712412662",
                        "ProductPrice" => 88
                    ],
                    [
                        "ProductName" => "Airforce Shoes",
                        "ProductImage" => "https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/AIR+FORCE+1+%2707.png",
                        "ProductPrice" => 90
                    ],
                    [
                        "ProductName" => "Green T-shirt",
                        "ProductImage" => "https://www.botnia.in/cdn/shop/files/5_41b6d8fa-fa23-4550-97f2-5161b85abcbd.png?v=1695274048",
                        "ProductPrice" => 20
                    ],
                    [
                        "ProductName" => "Indigo T-shirt",
                        "ProductImage" => "https://vestirio.com/cdn/shop/files/07.webp?v=1715426534",
                        "ProductPrice" => 70
                    ],
                    [
                        "ProductName" => "Convers Shoes",
                        "ProductImage" => "https://m.media-amazon.com/images/I/71J-XEv+ScL._AC_SL1500_.jpg",
                        "ProductPrice" => 35
                    ],

                ]
            ],
            [
                "id" => 2,
                "categoryName" =>  "Technology Category",
                "Products" => [
                    [
                        "ProductName" => "iPhone 15 Pro Max",
                        "ProductImage" => "https://btech.com/media/catalog/product/cache/d03d3d2a850049ac18b0f513075e81be/i/p/iphone-15-pro-natural_natural_titanium_5.jpg",
                        "ProductPrice" => 1000
                    ],
                    [
                        "ProductName" => "iPhone 16",
                        "ProductImage" => "https://mac-center.com.pr/cdn/shop/files/iPhone_16_Ultramarine_PDP_Image_Position_1__en-US_0a8bfd4e-c447-4ba1-8bec-83d22b45ef01.jpg?v=1726224187",
                        "ProductPrice" => 950
                    ],
                    [
                        "ProductName" => "Smart Watch",
                        "ProductImage" => "https://alsheikhstores.com/wp-content/uploads/2024/09/Apple-Watch-Series-10-Jet-Black-scaled.webp",
                        "ProductPrice" => 500
                    ],
                    [
                        "ProductName" => "PlayStation 5",
                        "ProductImage" => "https://2b.com.eg/media/catalog/product/cache/661473ab953cdcdf4c3b607144109b90/g/m/gm511-min_1.jpg",
                        "ProductPrice" => 1050
                    ],
                    [
                        "ProductName" => "indigo HeadPhone",
                        "ProductImage" => "https://wayupsports.com/cdn/shop/files/6925281981319_19ec1baf-f543-4916-9548-88084e9966ee.jpg?v=1707057121&width=800",
                        "ProductPrice" => 800
                    ],
                    [
                        "ProductName" => "Samsung s25",
                        "ProductImage" => "https://cdn.shortpixel.ai/spai/q_glossy+ret_img+to_webp/mobizil.com/wp-content/uploads/2025/01/Samsung-Galaxy-S23-5G.jpg",
                        "ProductPrice" => 1000
                    ],
                    [
                        "ProductName" => "Laptop Asus",
                        "ProductImage" => "https://m.media-amazon.com/images/I/713ODMAC83L._UF350,350_QL80_.jpg",
                        "ProductPrice" => 2000
                    ],
                    [
                        "ProductName" => "Indigo Mouse",
                        "ProductImage" => "https://imagedelivery.net/pjXEwQ5mgCM0WtJa4WheRQ/b592f438-9436-4355-d671-bf26df0c4c00/ProductViewThumb",
                        "ProductPrice" => 15
                    ],

                ]
            ],
            [
                "id" => 3,
                "categoryName" =>  "Vegetables&Fruits Category",
                "Products" => [
                    [
                        "ProductName" => "Apple",
                        "ProductImage" => "https://freshleafuae.com/wp-content/uploads/2024/08/red-apple-freshleaf-dubai-uae-img01.jpg",
                        "ProductPrice" => 5
                    ],
                    [
                        "ProductName" => "Orange",
                        "ProductImage" => "https://cdn.mafrservices.com/sys-master-root/hab/h1d/9342411112478/297733_main.jpg?im=Resize=1700",
                        "ProductPrice" => 4.5
                    ],
                    [
                        "ProductName" => "Tommato",
                        "ProductImage" => "https://www.heddensofwoodtown.co.uk/wp-content/uploads/2020/05/tomatoes_opt.jpg",
                        "ProductPrice" => 7
                    ],
                    [
                        "ProductName" => "Banana",
                        "ProductImage" => "https://static.wixstatic.com/media/2362b5_4ab326b8aa0440a98aaea0061d8b6d4d~mv2.jpg/v1/fill/w_480,h_480,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/2362b5_4ab326b8aa0440a98aaea0061d8b6d4d~mv2.jpg",
                        "ProductPrice" => 2
                    ],
                    [
                        "ProductName" => "Carrot",
                        "ProductImage" => "https://qmfarming.com/wp-content/uploads/2020/12/p9.jpg",
                        "ProductPrice" => 2.5
                    ],
                    [
                        "ProductName" => "Watermelon",
                        "ProductImage" => "https://mcprod.hyperone.com.eg/media/catalog/product/cache/8d4e6327d79fd11192282459179cc69e/2/3/2394001000007.jpg",
                        "ProductPrice" => 10
                    ],
                    [
                        "ProductName" => "Strawberry",
                        "ProductImage" => "https://cdn.salla.sa/oBEzY/42bfb5f8-e891-42d5-9171-09b3f8ad99fc-964.01985111663x1000-R5VUpAU5tlb7u1ODTqePGkJUATudtxcMpDGutWRz.jpg",
                        "ProductPrice" => 12
                    ],
                    [
                        "ProductName" => "Bell Pepper",
                        "ProductImage" => "https://cairofoodgroup.com/wp-content/uploads/2023/03/WhatsApp-Image-2023-03-21-at-11.03.49-PM-1.jpeg",
                        "ProductPrice" => 6
                    ],

                ]
            ],
        ];
        return $categories;
    }
}
