<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Tasty Trio Recipe</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .section-title {
            text-align: center;
            font-weight: 600;
            margin-bottom: 15px;
            color: #E75480;
        }
        
        .about-content, .mission-content {
            text-align: center;
            padding: 30px 20px;
        }

        .about-content p, .mission-content p {
            font-size: 16px;
            color: #555;
            max-width: 900px;
            margin: 0 auto;
        }

        .team-section {
            text-align: center;
            padding: 30px 15px; /* Reduced padding */
            background: white;
        }

        .team-members {
            display: flex;
            justify-content: center;
            gap: 20px; /* Reduced gap */
            margin-top: 20px; /* Reduced margin */
        }

        .team-member {
            text-align: center;
        }

        .team-member img {
            width: 120px; /* Reduced size */
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .team-member h4 {
            margin-top: 10px; /* Reduced margin */
            font-weight: 600;
            font-size: 16px; /* Slightly smaller font */
            color: #E75480;
        }

        .team-member p {
            font-weight: bold;
            color: #777;
            font-size: 14px; /* Slightly smaller font */
        }
    </style>
</head>
<body>

<section class="about-content">
    <h2 class="section-title">About Our Website</h2>
    <p>
        Tasty Trio Recipe is a platform designed to bring food lovers together. Whether you are a home cook or a professional chef,
        our website provides an easy way to share, discover, and enjoy new recipes. We aim to create a community where everyone 
        can explore different cuisines, improve their cooking skills, and share their culinary creations with the world.
    </p>
</section>

<section class="mission-content">
    <h2 class="section-title">Our Mission</h2>
    <p>
        Our mission is to make cooking more accessible, enjoyable, and inspiring. We believe in the power of sharing knowledge 
        and creativity, and we are dedicated to helping users find the perfect recipes tailored to their tastes.
    </p>
</section>

<section class="team-section">
    <h2 class="section-title">Meet Our Team</h2>
    <div class="team-members">
        <div class="team-member">
            <img src="assets/pic/TongJianHao.jpg" alt="Tong Jian Hao">
            <h4>Tong Jian Hao</h4>
            <p>Core Team</p>
        </div>
        <div class="team-member">
            <img src="assets/pic/ChenYongQi.jpg" alt="Chen Yong Qi">
            <h4>Chen Yong Qi</h4>
            <p>Core Team</p>
        </div>
        <div class="team-member">
            <img src="assets/pic/ChanJiaJun.jpg" alt="Chan Jia Jun">
            <h4>Chan Jia Jun</h4>
            <p>Core Team</p>
        </div>
    </div>
</section>

</body>
</html>
