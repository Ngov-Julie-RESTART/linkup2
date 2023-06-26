<?php

session_start();
if (isset($_SESSION["user_id"])) {
    
    $mysqli = require "db_conn.php";
    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();

}

require "../landing-page/database.php";

$requete = $database->prepare("SELECT * FROM tags");
$requete->execute();
$AllTags = $requete->fetchAll(PDO::FETCH_ASSOC);

$requete = $database->prepare("SELECT poster.user_id, poster.id, poster.contenu, poster.tag, poster.date, myprofile.pseudo, myprofile.bio, myprofile.file, user.name
                                FROM poster
                                INNER JOIN myprofile ON poster.user_id = myprofile.id
                                INNER JOIN user ON poster.user_id = user.id
                                ORDER BY poster.date DESC");
$requete->execute();
$Allposts = $requete->fetchAll(PDO::FETCH_ASSOC);

$userID = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linkup</title>
    <link rel="stylesheet" href="../landing-page/css/style.css">
    <script src="https://kit.fontawesome.com/d46d8a5065.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat&display=swap" rel="stylesheet">
</head>
<body>
<?php if (isset($user)){ ?>

    <main>
    <!-- Side-bar -->
        <section id="side-bar">
            <div class="logo">
                <h2 class="logo">LinkUP</h2>
            </div>
            <form action="" class="search">
                <input type="search" name="search" placeholder="Search post">
                <button type="submit">Search</button>
            </form>
            <div class="propriete">
                <p><i class="fa-solid fa-user"></i></i><a href="profile.php">Profile</a></p>
                <p><i class="fa-solid fa-right-from-bracket"></i><a href="logout.php">Log out</a></p>
                <p><i class="fa-solid fa-gear"></i><a href="#">Settings</a></p>
            </div>
        </section>

        <div class="barre">
            <a href="#" onclick="NavMenu()"><i class="fa-solid fa-bars"></i></a>
        </div>

        <section id="side-bar-mobile">
            <div class="logo">
                <a href="#" onclick="NavMenu()"><i class="fa-solid fa-bars"></i></a>
                <h2 class="logo">LinkUP</h2>
            </div>
            <form action="" class="search">
                <input type="search" name="search" placeholder="Search post">
                <button type="submit">Search</button>
            </form>
            <div class="propriete">
                <p><i class="fa-solid fa-user"></i></i><a href="profile.php">Profile</a></p>
                <p><i class="fa-solid fa-right-from-bracket"></i><a href="logout.php">Log out</a></p>
                <p><i class="fa-solid fa-gear"></i><a href="#">Settings</a></p>
            </div>
        </section>

        <section class="time-line">

            <section class="AllTags-mobile">
                <h2>Search by tags</h2>
                <div class="tags">
                    <div id="myBtnContainer">
                        <button class="btn active" onclick="filterSelection('all')">All</button>
                        <?php foreach($AllTags as $tag) { ?>
                            <button class="btn" onclick="filterSelection('<?= $tag['tag'] ?>')"><?= $tag['tag'] ?></button>
                        <?php } ?>
                    </div>
                    <form class="form" method="POST" action="tags.php">
                        <input class="tags" type="text" name="tag" placeholder="New tag">
                    </form>
                </div>
            </section>

            <?php foreach($Allposts as $posts){ ?>
                <div class="filterDiv <?= $posts['tag'] ?>">
                    <section class="post">
                        <div class="img-pfp">
                            <img src="../user/img/<?= $posts['file'] ?>" alt="pfp">
                        </div>
                        <section class="main-post">
                            <div class="name">
                                <h2><?= $posts['pseudo'] ?></h2>
                                <p><?= $posts['name'] ?></p>
                            </div>

                            <div class="text-post">
                                <p><?= $posts['contenu'] ?></p>
                            </div>

                            <div class="img-post">
                                <img src="https://fastly.picsum.photos/id/1064/200/200.jpg?hmac=xUH-ovzKEHg51S8vchfOZNAOcHB6b1TI_HzthmqvcWU" alt="image">
                            </div>
                        </section>
                    </section>

                    <div class="icons-post">
                        <div class="icon-post">
                            <p class="heart"><i class="fa-solid fa-heart"></i><a href="#">0</a></p>
                            <p class="comment"><i class="fa-sharp fa-solid fa-comment"></i><a href="#">0</a></p>
                            <div class="container">
                            <button class="btn"><?= $posts['tag'] ?></button>
                            </div>
                        </div>
                    <?php if($posts['user_id'] == $userID) { ?>
                        <div class="trash">
                            <a href="#" onclick="document.getElementById('id01').style.display='block'"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
    
                    <form action="../landing-page/delete.php" method="POST" id="delete">
                        <h1>Delete post?</h1>
                        <input type="hidden" name="supp" value="<?= $posts['id'] ?>">
                        <button type="submit">Yes</button>
                        <button type="button" onclick="closePopup()">No</button>
                    </form>
                <?php } ?>
                </div>
            <?php } ?>
        </section>

        <section class="AllTags">
            <h2>Search by tags</h2>
                <div class="tags">
                    <div id="myBtnContainer">
                        <button class="btn active" onclick="filterSelection('all')">All</button>
                        <?php foreach($AllTags as $tag) { ?>
                            <button class="btn" onclick="filterSelection('<?= $tag['tag'] ?>')"><?= $tag['tag'] ?></button>
                        <?php } ?>
                    </div>
                    <form class="form" method="POST" action="tags.php">
                        <input class="tags" type="text" name="tag" placeholder="New tag">
                    </form>
                </div>
        </section>
    </main>

<!-- Bouton poster -->
<p class="floating-button">
        <a href="#" onclick="openPost();"><i class="fa-solid fa-pen"></i></a>
</p>



<!--Forme pour faire un poste-->
    <section class="make-post" id="make-post">
        <h2 class="title-post">Make a post</h2>
        <form class="form" method="POST" action="../landing-page/insert-post.php">
            <input type="text" name="poster" value="<?= htmlspecialchars($_POST["contenu"] ?? "") ?>" placeholder="What's up?" required>
            
            <div class="tags">
                <label for="tags">Tags</label>
                <select name="tag" class="form-control">
                    <option value="">Select a tag</option>
                    <?php foreach($AllTags as $tag) { ?>
                        <option value="<?= $tag['tag'] ?>"><?= $tag['tag'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="bottom-post">
                <p><a href="#"><i class="fa-solid fa-image"></i></a></p>
                <div class="buttons">
                    <button type="submit">Post</button>
                    <button type="button" onclick="closePost();">Cancel</button>
                </div>
            </div>
            
        </form>
    </section>

    <?php }else{ ?>
        
        <p><a href="index.php">Log in</a> or <a href="signup.php">sign up</a></p>
        
    <?php }; ?>


<script src="java.js"></script>
<script src="../landing-page/java.js"></script>
</body>
</html>