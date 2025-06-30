<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Cafe Suki</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #7a4b47;
            --secondary: #ffbe5e;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        .pos-container {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            height: 100vh;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* Product Section */
        .product-section {
            padding: 20px;
            background-color: white;
            overflow-y: auto;
            position: relative;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            animation: fadeInDown 0.5s ease;
        }

        .header h2 {
            color: var(--primary);
            font-weight: 600;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h2 i {
            color: var(--secondary);
        }

        .search-box {
            position: relative;
            width: 300px;
            animation: fadeInRight 0.5s ease;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 30px;
            outline: none;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 2px 10px rgba(122, 75, 71, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #999;
        }

        .category-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
            animation: fadeIn 0.6s ease;
        }

        .category-tabs::-webkit-scrollbar {
            height: 5px;
        }

        .category-tabs::-webkit-scrollbar-thumb {
            background-color: rgba(122, 75, 71, 0.3);
            border-radius: 10px;
        }

        .category-tab {
            padding: 8px 20px;
            background-color: #f0f0f0;
            border-radius: 30px;
            font-size: 14px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.05);
        }

        .category-tab:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .category-tab.active {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 8px rgba(122, 75, 71, 0.2);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 20px;
            animation: fadeInUp 0.6s ease;
        }

        .product-card {
            border: 1px solid #eee;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            background: white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 130px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0));
            z-index: 1;
        }

        .product-info {
            padding: 12px;
            position: relative;
            z-index: 2;
        }

        .product-name {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 14px;
            color: var(--dark);
        }

        .product-price {
            color: var(--primary);
            font-weight: bold;
            font-size: 15px;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--secondary);
            color: white;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            z-index: 3;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Cart Section */
        .cart-section {
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
            border-left: 1px solid #ddd;
            position: relative;
        }

        .cart-header {
            padding: 20px;
            background-color: var(--primary);
            color: white;
            position: relative;
            animation: fadeInDown 0.5s ease;
        }

        .cart-header h2 {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-header h2 i {
            color: var(--secondary);
        }

        .cart-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            animation: fadeIn 0.6s ease;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
            animation: fadeInRight 0.4s ease;
        }

        .cart-item:hover {
            background-color: rgba(255, 255, 255, 0.7);
            transform: translateX(5px);
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 500;
            color: var(--dark);
        }

        .cart-item-price {
            color: var(--primary);
            font-size: 14px;
            font-weight: 600;
        }

        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: #eee;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--dark);
            font-weight: bold;
        }

        .quantity-btn:hover {
            background-color: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .quantity-btn.minus:hover {
            background-color: var(--danger);
        }

        .quantity-btn.plus:hover {
            background-color: var(--success);
        }

        .quantity-input {
            width: 40px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .quantity-input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .remove-btn {
            color: var(--danger);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.2s;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .remove-btn:hover {
            background-color: rgba(220, 53, 69, 0.1);
            transform: rotate(90deg);
        }

        .cart-summary {
            padding: 20px;
            background-color: white;
            border-top: 1px solid #ddd;
            animation: fadeInUp 0.5s ease;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .total-row {
            font-weight: bold;
            font-size: 18px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #6a403c;
            box-shadow: 0 5px 15px rgba(122, 75, 71, 0.2);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .empty-cart {
            text-align: center;
            color: #999;
            padding: 50px 0;
            animation: fadeIn 0.6s ease;
        }

        .empty-cart i {
            font-size: 50px;
            margin-bottom: 15px;
            color: #ddd;
            animation: bounce 2s infinite;
        }

        .empty-cart p {
            font-size: 16px;
            margin-top: 10px;
        }

        .customer-info {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            animation: fadeInDown 0.5s ease;
        }

        .customer-info h4 {
            margin-bottom: 10px;
            font-size: 16px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .customer-info h4 i {
            color: var(--secondary);
        }

        .customer-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .customer-input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        /* Style untuk fitur tambahan */
        .discount-section {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            animation: fadeInUp 0.5s ease;
        }

        .discount-section h4 {
            margin-bottom: 10px;
            font-size: 16px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .discount-section h4 i {
            color: var(--secondary);
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .payment-method {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .payment-method:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .payment-method.active {
            border-color: var(--primary);
            background-color: rgba(122, 75, 71, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(122, 75, 71, 0.1);
        }

        .payment-method i {
            font-size: 24px;
            margin-bottom: 5px;
            color: var(--primary);
        }

        .payment-method div {
            font-size: 12px;
            font-weight: 500;
        }

        .discount-input {
            display: flex;
            gap: 10px;
        }

        .discount-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .discount-input input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .discount-input button {
            padding: 10px 15px;
            background-color: var(--secondary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .discount-input button:hover {
            background-color: #ffb144;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(255, 190, 94, 0.2);
        }

        .notes-section {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            animation: fadeInUp 0.5s ease;
        }

        .notes-section h4 {
            margin-bottom: 10px;
            font-size: 16px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notes-section h4 i {
            color: var(--secondary);
        }

        .notes-section textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: vertical;
            min-height: 80px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .notes-section textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .change-section {
            display: none;
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.5s ease;
        }

        .change-section h5 {
            margin-bottom: 10px;
            font-size: 15px;
            color: var(--primary);
        }

        .cash-input {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .cash-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .cash-input input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 75, 71, 0.1);
        }

        .cash-input button {
            padding: 10px 15px;
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cash-input button:hover {
            background-color: #6a403c;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(122, 75, 71, 0.2);
        }

        .action-buttons {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .btn-warning {
            background-color: var(--secondary);
            color: white;
        }

        .btn-warning:hover {
            background-color: #ffb144;
            box-shadow: 0 5px 15px rgba(255, 190, 94, 0.2);
        }

        .receipt-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .receipt-content {
            background-color: white;
            width: 90%;
            max-width: 400px;
            border-radius: 15px;
            padding: 25px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header img {
            max-width: 180px;
            margin-bottom: 15px;
            filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.1));
        }

        .receipt-header h3 {
            color: var(--primary);
            margin-bottom: 5px;
            font-size: 1.5rem;
        }

        .receipt-header p {
            color: #666;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .receipt-details {
            margin-bottom: 20px;
            font-size: 13px;
        }

        .receipt-details div {
            margin-bottom: 5px;
        }

        .receipt-items {
            margin: 20px 0;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .receipt-total {
            border-top: 1px dashed #333;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 14px;
        }

        .receipt-total div {
            margin-bottom: 8px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #666;
            line-height: 1.5;
        }

        .close-receipt {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 24px;
            color: var(--danger);
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .close-receipt:hover {
            background-color: rgba(220, 53, 69, 0.1);
            transform: rotate(90deg);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-15px);
            }

            60% {
                transform: translateY(-7px);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Floating animation for product cards */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Floating effect on hover */
        .product-card:hover {
            animation: float 2s ease-in-out infinite;
        }

        /* Notification badge */
        .notification-badge {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(122, 75, 71, 0.3);
            z-index: 100;
            transition: all 0.3s;
            animation: pulse 2s infinite;
        }

        .notification-badge:hover {
            transform: scale(1.1);
            animation: none;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .pos-container {
                grid-template-columns: 1fr;
            }

            .cart-section {
                border-left: none;
                border-top: 1px solid #ddd;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .search-box {
                width: 100%;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>

<body>
    <div class="pos-container">
        <!-- Product Section -->
        <div class="product-section">
            <div class="header">
                <h2><i class="fas fa-mug-hot"></i> Menu Cafe Suki</h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari menu..." id="search-input">
                </div>
            </div>

            <div class="category-tabs">
                <div class="category-tab active" onclick="filterByCategory('Semua')">Semua</div>
                <div class="category-tab" onclick="filterByCategory('Minuman')"><i class="fas fa-coffee"></i> Minuman
                </div>
                <div class="category-tab" onclick="filterByCategory('Makanan')"><i class="fas fa-utensils"></i> Makanan
                </div>
                <div class="category-tab" onclick="filterByCategory('Snack')"><i class="fas fa-cookie"></i> Snack</div>
                <div class="category-tab" onclick="filterByCategory('Promo')"><i class="fas fa-tag"></i> Promo</div>
            </div>

            <div class="product-grid" id="product-grid">
                <!-- Minuman -->
                <div class="product-card" data-id="1" data-category="Minuman">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2FwcHVjY2lub3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"
                            alt="Cappuccino">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Cappuccino</div>
                        <div class="product-price">Rp 25.000</div>
                    </div>
                </div>

                <div class="product-card" data-id="2" data-category="Minuman">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1568649929103-28ffbefaca1e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8dGVoJTIwdGFyaWt8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60"
                            alt="Teh Tarik">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Teh Tarik</div>
                        <div class="product-price">Rp 15.000</div>
                    </div>
                </div>

                <div class="product-card" data-id="3" data-category="Minuman">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2FwcHVjY2lub3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"
                            alt="Kopi Susu">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Kopi Susu</div>
                        <div class="product-price">Rp 20.000</div>
                    </div>
                </div>

                <div class="product-card" data-id="4" data-category="Minuman">
                    <div class="product-badge">New!</div>
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1551029506-0807df4e2031?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8bWFuZ29qfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60"
                            alt="Jus Mangga">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Jus Mangga</div>
                        <div class="product-price">Rp 18.000</div>
                    </div>
                </div>

                <!-- Makanan -->
                <div class="product-card" data-id="5" data-category="Makanan">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1630917765361-5e3f8a8a3b0d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fG5hc2klMjBnb3Jlbmd8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60"
                            alt="Nasi Goreng">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Nasi Goreng Spesial</div>
                        <div class="product-price">Rp 30.000</div>
                    </div>
                </div>

                <div class="product-card" data-id="6" data-category="Makanan">
                    <div class="product-badge">Hot!</div>
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bWllJTIwZ29yZW5nfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60"
                            alt="Mie Goreng">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Mie Goreng Jawa</div>
                        <div class="product-price">Rp 28.000</div>
                    </div>
                </div>

                <div class="product-card" data-id="7" data-category="Makanan">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cm90aSUyMGJha2FyfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60"
                            alt="Roti Bakar">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Roti Bakar Coklat Keju</div>
                        <div class="product-price">Rp 22.000</div>
                    </div>
                </div>

                <!-- Snack -->
                <div class="product-card" data-id="8" data-category="Snack">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a2VudGFuZyUyMGdvcmVuZ3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"
                            alt="Kentang Goreng">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Kentang Goreng</div>
                        <div class="product-price">Rp 25.000</div>
                    </div>
                </div>

                <div class="product-card" data-id="9" data-category="Snack">
                    <div class="product-badge">Promo</div>
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1558312651-5b0c0c4a5b0a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cGFuY2FrZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"
                            alt="Pancake">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Pancake Maple</div>
                        <div class="product-price">Rp 28.000</div>
                    </div>
                </div>

                <div class="product-card" data-id="10" data-category="Snack">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1563805042-7684c019e1cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8ZG9udXR8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60"
                            alt="Donat">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Donat Glaze</div>
                        <div class="product-price">Rp 18.000</div>
                    </div>
                </div>

                <!-- Promo Items -->
                <div class="product-card" data-id="11" data-category="Promo">
                    <div class="product-badge">-20%</div>
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1510626176961-4b57d4fbad03?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Y2FrZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60"
                            alt="Red Velvet">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Red Velvet Cake</div>
                        <div class="product-price"><span
                                style="text-decoration: line-through; color: #999; font-size: 13px;">Rp 45.000</span> Rp
                            36.000</div>
                    </div>
                </div>

                <div class="product-card" data-id="12" data-category="Promo">
                    <div class="product-badge">Combo</div>
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YnVyZ2VyfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60"
                            alt="Burger">
                    </div>
                    <div class="product-info">
                        <div class="product-name">Burger + Kentang</div>
                        <div class="product-price"><span
                                style="text-decoration: line-through; color: #999; font-size: 13px;">Rp 55.000</span> Rp
                            45.000</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section">
            <div class="cart-header">
                <h2><i class="fas fa-shopping-cart"></i> Pesanan</h2>
            </div>

            <div class="cart-body">
                <div class="customer-info">
                    <h4><i class="fas fa-user"></i> Informasi Pelanggan</h4>
                    <input type="text" class="customer-input" placeholder="Nomor Meja" id="table-number">
                    <input type="text" class="customer-input" placeholder="Nama Pelanggan (Opsional)"
                        id="customer-name">
                </div>

                <div class="discount-section">
                    <h4><i class="fas fa-tag"></i> Diskon</h4>
                    <div class="discount-input">
                        <input type="text" id="discount-code" placeholder="Kode diskon">
                        <button onclick="applyDiscount()">Terapkan</button>
                    </div>
                    <div id="discount-info"
                        style="display: none; margin-top: 10px; color: var(--success); font-size: 13px;"></div>
                </div>

                <div class="payment-methods-section">
                    <h4><i class="fas fa-credit-card"></i> Metode Pembayaran</h4>
                    <div class="payment-methods">
                        <div class="payment-method" onclick="selectPaymentMethod('cash')">
                            <i class="fas fa-money-bill-wave"></i>
                            <div>Tunai</div>
                        </div>
                        <div class="payment-method" onclick="selectPaymentMethod('debit')">
                            <i class="fas fa-credit-card"></i>
                            <div>Kartu Debit</div>
                        </div>
                        <div class="payment-method" onclick="selectPaymentMethod('credit')">
                            <i class="far fa-credit-card"></i>
                            <div>Kartu Kredit</div>
                        </div>
                        <div class="payment-method" onclick="selectPaymentMethod('qris')">
                            <i class="fas fa-qrcode"></i>
                            <div>QRIS</div>
                        </div>
                        <div class="payment-method" onclick="selectPaymentMethod('ewallet')">
                            <i class="fas fa-wallet"></i>
                            <div>E-Wallet</div>
                        </div>
                        <div class="payment-method" onclick="selectPaymentMethod('transfer')">
                            <i class="fas fa-exchange-alt"></i>
                            <div>Transfer</div>
                        </div>
                    </div>

                    <div id="cash-payment" class="change-section">
                        <h5><i class="fas fa-calculator"></i> Pembayaran Tunai</h5>
                        <div class="cash-input">
                            <input type="number" id="cash-amount" placeholder="Jumlah uang">
                            <button onclick="calculateChange()">Hitung</button>
                        </div>
                        <div id="change-result" style="margin-top: 10px; font-size: 14px;"></div>
                    </div>
                </div>

                <div class="notes-section">
                    <h4><i class="fas fa-sticky-note"></i> Catatan</h4>
                    <textarea id="order-notes"
                        placeholder="Catatan untuk pesanan (contoh: pedas, tidak pakai bawang, dll)"></textarea>
                </div>

                <!-- Cart Items -->
                <div id="cart-items">
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Belum ada pesanan</p>
                        <p style="font-size: 14px; margin-top: 5px;">Klik item menu untuk menambahkan ke keranjang</p>
                    </div>
                </div>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span>Diskon:</span>
                    <span id="discount-amount">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span>Pajak (10%):</span>
                    <span id="tax">Rp 0</span>
                </div>
                <div class="summary-row total-row">
                    <span>Total:</span>
                    <span id="total">Rp 0</span>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-secondary" onclick="clearCart()">
                        <i class="fas fa-trash"></i> Kosongkan
                    </button>
                    <button class="btn btn-warning" onclick="holdOrder()">
                        <i class="fas fa-pause"></i> Hold
                    </button>
                    <button class="btn btn-success" onclick="processPayment()">
                        <i class="fas fa-print"></i> Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification badge for held orders -->
    <div class="notification-badge" id="heldOrdersBadge" style="display: none;" onclick="showHeldOrders()">
        <i class="fas fa-pause"></i>
        <span id="heldOrdersCount"
            style="position: absolute; font-size: 12px; bottom: -5px; right: -5px; background: var(--danger); width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"></span>
    </div>

    <!-- Held Orders Modal -->
    <div class="receipt-modal" id="heldOrdersModal">
        <div class="receipt-content" style="max-width: 500px;">
            <div class="close-receipt" onclick="closeHeldOrders()">&times;</div>
            <div class="receipt-header">
                <h3><i class="fas fa-pause"></i> Pesanan Tertahan</h3>
                <p>Daftar pesanan yang sedang dihold</p>
            </div>

            <div id="held-orders-list" style="max-height: 60vh; overflow-y: auto;">
                <!-- Held orders will be displayed here -->
            </div>

            <div class="receipt-footer">
                <p>Klik pesanan untuk memuatnya kembali ke keranjang</p>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div class="receipt-modal" id="receiptModal">
        <div class="receipt-content">
            <div class="close-receipt" onclick="closeReceipt()">&times;</div>
            <div class="receipt-header">
                <img src="https://via.placeholder.com/200x60?text=Cafe+Suki&font=poppins" alt="Cafe Suki">
                <h3>Struk Pembayaran</h3>
                <p>Jl. Contoh No. 123, Kota</p>
                <p>Telp: 08123456789</p>
            </div>

            <div class="receipt-details">
                <div><strong>No. Transaksi:</strong> TRX-<span id="receipt-number"></span></div>
                <div><strong>Tanggal:</strong> <span id="receipt-date"></span></div>
                <div><strong>Pelanggan:</strong> <span id="receipt-customer"></span></div>
                <div><strong>Kasir:</strong> <span id="receipt-cashier">Admin</span></div>
            </div>

            <div class="receipt-items">
                <div style="border-bottom: 1px dashed #333; padding-bottom: 5px; margin-bottom: 5px;">
                    <div style="display: flex; justify-content: space-between;">
                        <div><strong>Item</strong></div>
                        <div><strong>Total</strong></div>
                    </div>
                </div>
                <div id="receipt-items-list"></div>
            </div>

            <div class="receipt-total">
                <div style="display: flex; justify-content: space-between;">
                    <div>Subtotal:</div>
                    <div id="receipt-subtotal"></div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <div>Diskon:</div>
                    <div id="receipt-discount"></div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <div>Pajak (10%):</div>
                    <div id="receipt-tax"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold;">
                    <div>Total:</div>
                    <div id="receipt-total"></div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                    <div>Pembayaran:</div>
                    <div id="receipt-payment-method"></div>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <div>Tunai:</div>
                    <div id="receipt-cash"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold;">
                    <div>Kembalian:</div>
                    <div id="receipt-change"></div>
                </div>
            </div>

            <div class="receipt-footer">
                <p>Terima kasih telah berkunjung ke Cafe Suki</p>
                <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
            </div>
        </div>
    </div>

    <script>
        // Data produk lengkap
        const products = [
            { id: 1, name: "Cappuccino", price: 25000, category: "Minuman", image: "https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2FwcHVjY2lub3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60" },
            { id: 2, name: "Teh Tarik", price: 15000, category: "Minuman", image: "https://images.unsplash.com/photo-1568649929103-28ffbefaca1e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8dGVoJTIwdGFyaWt8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60" },
            { id: 3, name: "Kopi Susu", price: 20000, category: "Minuman", image: "https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2FwcHVjY2lub3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60" },
            { id: 4, name: "Jus Mangga", price: 18000, category: "Minuman", image: "https://images.unsplash.com/photo-1551029506-0807df4e2031?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8bWFuZ29qfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60", isNew: true },
            { id: 5, name: "Nasi Goreng Spesial", price: 30000, category: "Makanan", image: "https://images.unsplash.com/photo-1630917765361-5e3f8a8a3b0d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fG5hc2klMjBnb3Jlbmd8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60" },
            { id: 6, name: "Mie Goreng Jawa", price: 28000, category: "Makanan", image: "https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bWllJTIwZ29yZW5nfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60", isHot: true },
            { id: 7, name: "Roti Bakar Coklat Keju", price: 22000, category: "Makanan", image: "https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cm90aSUyMGJha2FyfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60" },
            { id: 8, name: "Kentang Goreng", price: 25000, category: "Snack", image: "https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8a2VudGFuZyUyMGdvcmVuZ3xlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60" },
            { id: 9, name: "Pancake Maple", price: 28000, category: "Snack", image: "https://images.unsplash.com/photo-1558312651-5b0c0c4a5b0a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cGFuY2FrZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60", isPromo: true },
            { id: 10, name: "Donat Glaze", price: 18000, category: "Snack", image: "https://images.unsplash.com/photo-1563805042-7684c019e1cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8ZG9udXR8ZW58MHx8MHx8fDA%3D&auto=format&fit=crop&w=500&q=60" },
            { id: 11, name: "Red Velvet Cake", price: 36000, originalPrice: 45000, category: "Promo", image: "https://images.unsplash.com/photo-1510626176961-4b57d4fbad03?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8Y2FrZXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60", discount: "20%" },
            { id: 12, name: "Burger + Kentang", price: 45000, originalPrice: 55000, category: "Promo", image: "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YnVyZ2VyfGVufDB8fDB8fHww&auto=format&fit=crop&w=500&q=60", isCombo: true }
        ];

        let cart = [];
        let selectedPaymentMethod = null;
        let discount = 0;
        let discountType = 'amount'; // 'amount' atau 'percentage'
        let heldOrders = [];
        let transactionCounter = 1;
        let currentCategory = "Semua";

        // Format currency
        function formatCurrency(amount) {
            return "Rp " + amount.toLocaleString("id-ID");
        }

        // Update cart display
        function updateCart() {
            const cartItemsContainer = document.getElementById("cart-items");

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Belum ada pesanan</p>
                        <p style="font-size: 14px; margin-top: 5px;">Klik item menu untuk menambahkan ke keranjang</p>
                    </div>
                `;
            } else {
                let cartHTML = "";
                let subtotal = 0;

                cart.forEach(item => {
                    const product = products.find(p => p.id === item.id);
                    const itemPrice = product.originalPrice ? product.price : product.price;
                    const itemTotal = itemPrice * item.quantity;
                    subtotal += itemTotal;

                    cartHTML += `
                        <div class="cart-item" data-id="${item.id}">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${product.name}</div>
                                <div class="cart-item-price">${formatCurrency(itemPrice)}</div>
                            </div>
                            <div class="cart-item-controls">
                                <button class="quantity-btn minus" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <input type="number" class="quantity-input" value="${item.quantity}" min="1"
                                    onchange="setQuantity(${item.id}, this.value)">
                                <button class="quantity-btn plus" onclick="updateQuantity(${item.id}, 1)">+</button>
                                <button class="remove-btn" onclick="removeFromCart(${item.id})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });

                cartItemsContainer.innerHTML = cartHTML;

                // Hitung diskon
                let totalDiscount = 0;
                if (discountType === 'percentage') {
                    totalDiscount = subtotal * (discount / 100);
                } else {
                    totalDiscount = discount;
                }

                const afterDiscount = subtotal - totalDiscount;
                const tax = afterDiscount * 0.1; // Pajak 10%
                const total = afterDiscount + tax;

                document.getElementById("subtotal").textContent = formatCurrency(subtotal);
                document.getElementById("discount-amount").textContent = formatCurrency(totalDiscount);
                document.getElementById("tax").textContent = formatCurrency(tax);
                document.getElementById("total").textContent = formatCurrency(total);

                // Tampilkan info diskon jika ada
                if (totalDiscount > 0) {
                    document.getElementById("discount-info").style.display = "block";
                    document.getElementById("discount-info").textContent =
                        discountType === 'percentage' ?
                        `Diskon ${discount}% diterapkan` :
                        `Diskon ${formatCurrency(discount)} diterapkan`;
                } else {
                    document.getElementById("discount-info").style.display = "none";
                }
            }

            // Update held orders badge
            updateHeldOrdersBadge();
        }

        // Add to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);

            if (product) {
                const existingItem = cart.find(item => item.id === productId);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        id: product.id,
                        quantity: 1
                    });
                }

                updateCart();

                // Animation feedback
                const productCard = document.querySelector(`.product-card[data-id="${productId}"]`);
                if (productCard) {
                    productCard.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        productCard.style.transform = '';
                    }, 200);
                }
            }
        }

        // Update quantity
        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);

            if (item) {
                item.quantity += change;

                if (item.quantity < 1) {
                    item.quantity = 1;
                }

                updateCart();
            }
        }

        // Set quantity
        function setQuantity(productId, quantity) {
            const item = cart.find(item => item.id === productId);

            if (item) {
                const newQuantity = parseInt(quantity);

                if (newQuantity >= 1) {
                    item.quantity = newQuantity;
                    updateCart();
                } else {
                    item.quantity = 1;
                    updateCart();
                }
            }
        }

        // Remove from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        // Clear cart
        function clearCart() {
            if (cart.length === 0) return;

            if (confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
                cart = [];
                discount = 0;
                discountType = 'amount';
                document.getElementById("discount-code").value = "";
                document.getElementById("discount-info").style.display = "none";
                updateCart();
            }
        }

        // Filter by category
        function filterByCategory(category) {
            currentCategory = category;
            const categoryTabs = document.querySelectorAll(".category-tab");
            const productCards = document.querySelectorAll(".product-card");

            categoryTabs.forEach(tab => {
                if (tab.textContent === category || tab.getAttribute("onclick").includes(category)) {
                    tab.classList.add("active");
                } else {
                    tab.classList.remove("active");
                }
            });

            productCards.forEach(card => {
                if (category === "Semua" || card.getAttribute("data-category") === category) {
                    card.style.display = "block";
                    card.style.animation = "fadeInUp 0.4s ease";
                } else {
                    card.style.display = "none";
                }
            });

            // Scroll to top of product grid
            document.querySelector('.product-grid').scrollIntoView({ behavior: 'smooth' });
        }

        // Search products
        function searchProducts() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const productCards = document.querySelectorAll(".product-card");

            productCards.forEach(card => {
                const productName = card.querySelector('.product-name').textContent.toLowerCase();
                const shouldShow = (currentCategory === "Semua" || card.getAttribute("data-category") === currentCategory) &&
                                 (productName.includes(searchTerm));

                card.style.display = shouldShow ? "block" : "none";
                if (shouldShow) {
                    card.style.animation = "fadeInUp 0.4s ease";
                }
            });
        }

        // Select payment method
        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;

            // Update UI
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // Tampilkan input tunai jika metode tunai dipilih
            if (method === 'cash') {
                document.getElementById('cash-payment').style.display = 'block';
            } else {
                document.getElementById('cash-payment').style.display = 'none';
            }
        }

        // Apply discount
        function applyDiscount() {
            const discountCode = document.getElementById('discount-code').value;

            // Contoh logika diskon sederhana
            if (discountCode === 'DISKON10') {
                discount = 10;
                discountType = 'percentage';
                document.getElementById('discount-info').style.display = 'block';
                document.getElementById('discount-info').textContent = 'Diskon 10% berhasil diterapkan';
                document.getElementById('discount-info').style.color = 'var(--success)';
            } else if (discountCode === 'DISKON5K') {
                discount = 5000;
                discountType = 'amount';
                document.getElementById('discount-info').style.display = 'block';
                document.getElementById('discount-info').textContent = 'Diskon Rp 5.000 berhasil diterapkan';
                document.getElementById('discount-info').style.color = 'var(--success)';
            } else if (discountCode) {
                document.getElementById('discount-info').style.display = 'block';
                document.getElementById('discount-info').textContent = 'Kode diskon tidak valid';
                document.getElementById('discount-info').style.color = 'var(--danger)';
                discount = 0;
            } else {
                discount = 0;
                document.getElementById('discount-info').style.display = 'none';
            }

            updateCart();
        }

        // Calculate change
        function calculateChange() {
            const cashAmount = parseFloat(document.getElementById('cash-amount').value);
            const total = getTotalAmount();

            if (isNaN(cashAmount)) {
                document.getElementById('change-result').innerHTML = `
                    <strong style="color: var(--danger)">Masukkan jumlah uang</strong>
                `;
                return;
            }

            if (cashAmount >= total) {
                const change = cashAmount - total;
                document.getElementById('change-result').innerHTML = `
                    <strong>Kembalian:</strong> ${formatCurrency(change)}
                `;
            } else {
                document.getElementById('change-result').innerHTML = `
                    <strong style="color: var(--danger)">Uang kurang:</strong> ${formatCurrency(total - cashAmount)}
                `;
            }
        }

        // Get total amount after discount
        function getTotalAmount() {
            let subtotal = cart.reduce((sum, item) => {
                const product = products.find(p => p.id === item.id);
                const itemPrice = product.originalPrice ? product.price : product.price;
                return sum + (itemPrice * item.quantity);
            }, 0);

            let totalDiscount = 0;

            if (discountType === 'percentage') {
                totalDiscount = subtotal * (discount / 100);
            } else {
                totalDiscount = discount;
            }

            const afterDiscount = subtotal - totalDiscount;
            const tax = afterDiscount * 0.1; // Pajak 10%
            return afterDiscount + tax;
        }

        // Hold order
        function holdOrder() {
            if (cart.length === 0) {
                alert('Tidak ada pesanan untuk dihold');
                return;
            }

            const tableNumber = document.getElementById('table-number').value || 'Tanpa Meja';
            const customerName = document.getElementById('customer-name').value || '';
            const order = {
                id: new Date().getTime(),
                table: tableNumber,
                customer: customerName,
                items: [...cart],
                time: new Date().toLocaleTimeString(),
                subtotal: cart.reduce((sum, item) => {
                    const product = products.find(p => p.id === item.id);
                    const itemPrice = product.originalPrice ? product.price : product.price;
                    return sum + (itemPrice * item.quantity);
                }, 0)
            };

            heldOrders.push(order);
            clearCart();

            // Show notification
            const notification = document.createElement('div');
            notification.innerHTML = `
                <div style="position: fixed; bottom: 20px; left: 20px; background: var(--primary); color: white; padding: 10px 15px; border-radius: 5px; box-shadow: 0 3px 10px rgba(0,0,0,0.2); z-index: 100; animation: fadeInRight 0.3s ease;">
                    Pesanan untuk ${customerName ? customerName + ' di ' : ''}meja ${tableNumber} berhasil dihold
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'fadeOutRight 0.3s ease';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Update held orders badge
        function updateHeldOrdersBadge() {
            const badge = document.getElementById('heldOrdersBadge');
            const countElement = document.getElementById('heldOrdersCount');

            if (heldOrders.length > 0) {
                badge.style.display = 'flex';
                countElement.textContent = heldOrders.length;
            } else {
                badge.style.display = 'none';
            }
        }

        // Show held orders
        function showHeldOrders() {
            if (heldOrders.length === 0) return;

            const modal = document.getElementById('heldOrdersModal');
            const list = document.getElementById('held-orders-list');

            list.innerHTML = '';

            heldOrders.forEach((order, index) => {
                const orderElement = document.createElement('div');
                orderElement.className = 'held-order';
                orderElement.style.padding = '15px';
                orderElement.style.borderBottom = '1px solid #eee';
                orderElement.style.cursor = 'pointer';
                orderElement.style.transition = 'all 0.3s';
                orderElement.innerHTML = `
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <strong>${order.customer ? order.customer : 'Pelanggan'} - Meja ${order.table}</strong>
                        <span style="color: #666; font-size: 13px;">${order.time}</span>
                    </div>
                    <div style="font-size: 13px; color: #666; margin-bottom: 5px;">
                        ${order.items.length} item - ${formatCurrency(order.subtotal)}
                    </div>
                    <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                        ${order.items.slice(0, 3).map(item => {
                            const product = products.find(p => p.id === item.id);
                            return `<span style="background: #f0f0f0; padding: 2px 8px; border-radius: 10px; font-size: 12px;">${product.name} x${item.quantity}</span>`;
                        }).join('')}
                        ${order.items.length > 3 ? '<span style="background: #f0f0f0; padding: 2px 8px; border-radius: 10px; font-size: 12px;">+'
                         + (order.items.length - 3) + ' lagi</span>' : ''}
                    </div>
                `;

                orderElement.addEventListener('click', () => {
                    loadHeldOrder(index);
                });

                orderElement.addEventListener('mouseenter', () => {
                    orderElement.style.background = 'rgba(122, 75, 71, 0.05)';
                });

                orderElement.addEventListener('mouseleave', () => {
                    orderElement.style.background = '';
                });

                list.appendChild(orderElement);
            });

            modal.style.display = 'flex';
        }

        // Close held orders modal
        function closeHeldOrders() {
            document.getElementById('heldOrdersModal').style.display = 'none';
        }

        // Load held order into cart
        function loadHeldOrder(index) {
            const order = heldOrders[index];

            // Set customer info
            document.getElementById('table-number').value = order.table;
            document.getElementById('customer-name').value = order.customer || '';

            // Set cart items
            cart = [...order.items];
            updateCart();

            // Remove from held orders
            heldOrders.splice(index, 1);
            updateHeldOrdersBadge();
            closeHeldOrders();

            // Show notification
            const notification = document.createElement('div');
            notification.innerHTML = `
                <div style="position: fixed; bottom: 20px; left: 20px; background: var(--success); color: white; padding: 10px 15px; border-radius: 5px; box-shadow: 0 3px 10px rgba(0,0,0,0.2); z-index: 100; animation: fadeInRight 0.3s ease;">
                    Pesanan berhasil dimuat ke keranjang
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'fadeOutRight 0.3s ease';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Process payment
        function processPayment() {
            if (cart.length === 0) {
                showAlert('Keranjang kosong, tidak ada yang dibayar', 'danger');
                return;
            }

            if (!selectedPaymentMethod) {
                showAlert('Silakan pilih metode pembayaran', 'danger');
                return;
            }

            const tableNumber = document.getElementById('table-number').value;
            if (!tableNumber) {
                showAlert('Silakan masukkan nomor meja', 'danger');
                return;
            }

            if (selectedPaymentMethod === 'cash') {
                const cashAmount = parseFloat(document.getElementById('cash-amount').value || 0);
                const total = getTotalAmount();

                if (cashAmount < total) {
                    showAlert('Jumlah uang tunai kurang', 'danger');
                    return;
                }
            }

            // Tampilkan struk
            showReceipt();
        }

        // Show alert
        function showAlert(message, type) {
            const alert = document.createElement('div');
            alert.innerHTML = `
                <div style="position: fixed; bottom: 20px; left: 20px; background: var(--${type}); color: white; padding: 10px 15px; border-radius: 5px; box-shadow: 0 3px 10px rgba(0,0,0,0.2); z-index: 100; animation: fadeInRight 0.3s ease;">
                    ${message}
                </div>
            `;
            document.body.appendChild(alert);

            setTimeout(() => {
                alert.style.animation = 'fadeOutRight 0.3s ease';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 3000);
        }

        // Show receipt
        function showReceipt() {
            const now = new Date();
            const tableNumber = document.getElementById('table-number').value || '-';
            const customerName = document.getElementById('customer-name').value || '-';
            const notes = document.getElementById('order-notes').value || '-';

            // Generate receipt number
            const receiptNumber = `TRX-${now.getFullYear()}${(now.getMonth()+1).toString().padStart(2, '0')}${now.getDate().toString().padStart(2, '0')}-${transactionCounter.toString().padStart(3, '0')}`;
            transactionCounter++;

            // Update receipt data
            document.getElementById('receipt-number').textContent = receiptNumber;
            document.getElementById('receipt-date').textContent = now.toLocaleString('id-ID');
            document.getElementById('receipt-customer').textContent = tableNumber + (customerName !== '-' ? ` (${customerName})` : '');

            // Item pesanan
            let itemsHTML = '';
            let subtotal = 0;

            cart.forEach(item => {
                const product = products.find(p => p.id === item.id);
                const itemPrice = product.originalPrice ? product.price : product.price;
                const itemTotal = itemPrice * item.quantity;
                subtotal += itemTotal;

                itemsHTML += `
                    <div class="receipt-item">
                        <div>${product.name} x${item.quantity}</div>
                        <div>${formatCurrency(itemTotal)}</div>
                    </div>
                `;
            });

            document.getElementById('receipt-items-list').innerHTML = itemsHTML;

            // Perhitungan
            let totalDiscount = discountType === 'percentage' ? subtotal * (discount / 100) : discount;
            let afterDiscount = subtotal - totalDiscount;
            let tax = afterDiscount * 0.1;
            let total = afterDiscount + tax;

            document.getElementById('receipt-subtotal').textContent = formatCurrency(subtotal);
            document.getElementById('receipt-discount').textContent = `- ${formatCurrency(totalDiscount)}`;
            document.getElementById('receipt-tax').textContent = formatCurrency(tax);
            document.getElementById('receipt-total').textContent = formatCurrency(total);

            // Pembayaran
            const paymentMethods = {
                'cash': 'Tunai',
                'debit': 'Kartu Debit',
                'credit': 'Kartu Kredit',
                'qris': 'QRIS',
                'ewallet': 'E-Wallet',
                'transfer': 'Transfer Bank'
            };

            document.getElementById('receipt-payment-method').textContent = paymentMethods[selectedPaymentMethod];

            if (selectedPaymentMethod === 'cash') {
                const cashAmount = parseFloat(document.getElementById('cash-amount').value || 0);
                document.getElementById('receipt-cash').textContent = formatCurrency(cashAmount);
                document.getElementById('receipt-change').textContent = formatCurrency(cashAmount - total);
            } else {
                document.getElementById('receipt-cash').textContent = '-';
                document.getElementById('receipt-change').textContent = '-';
            }

            // Tampilkan modal struk
            document.getElementById('receiptModal').style.display = 'flex';
        }

        // Close receipt
        function closeReceipt() {
            document.getElementById('receiptModal').style.display = 'none';
            clearCart();
        }

        // Initialize event listeners
        document.addEventListener("DOMContentLoaded", function() {
            // Add click event to all product cards
            const productCards = document.querySelectorAll(".product-card");
            productCards.forEach(card => {
                card.addEventListener("click", function() {
                    const productId = parseInt(card.getAttribute("data-id"));
                    addToCart(productId);
                });
            });

            // Search functionality
            document.getElementById('search-input').addEventListener('input', searchProducts);

            // Set default payment method to cash
            selectPaymentMethod('cash');
        });
    </script>
</body>

</html>