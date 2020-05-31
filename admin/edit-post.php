<?php require_once('./includes/header.php'); ?>
<?php 

  if (!isset($_COOKIE['_ua_'])) {
    header("Location: sign-in.php");
  }

?>

<?php 
    /* Informa o nível dos erros que serão exibidos */
    error_reporting(E_ALL);
    /* Habilita a exibição de erros */
    ini_set("display_errors", 1);


    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: index.php");
    }

    if (isset($_POST['val'])) {
        $pid = $_POST['val'];
        $sql = "SELECT * FROM posts WHERE post_id = :pid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pid' => $pid
        ]);
    
        while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $post_id = $post['post_id'];
            $post_title = $post['post_title'];
            $post_cat_id = $post['post_cat_id'];
            $post_status = $post['post_status']; 
            $post_image = $post['post_image'];
            $post_content = $post['post_des'];
        }
    }                                           

?>
<body>
    <div class="fluid-container">
        <?php require_once('./includes/navigation.php'); ?>

        <section id="main" class="mx-lg-5 mx-md-2 mx-sm-2 pt-3">
            <h2 class="pb-3">Edit Post</h2>

            <?php 
                /* Informa o nível dos erros que serão exibidos */
                error_reporting(E_ALL);
                /* Habilita a exibição de erros */
                ini_set("display_errors", 1);

                if (isset($_POST['update-post'])) {
                    $post_title = $_POST['post-title'];
                    $post_cat_id = $_POST['cat-id'];
                    $post_status = $_POST['post-status'];
                    $post_content = $_POST['post-content'];
                    $postid = $_POST['edit-post-id'];
                    $post_date = date('j F Y');
                    $post_author = "mdabarik";
                    $post_image = $_FILES['post-image']['name'];
                    $post_temp_image = $_FILES['post-image']['tmp_name'];
                    move_uploaded_file($post_temp_image, "../img/{$post_image}");

                    if (empty($post_image)) {
                        $sqli = "SELECT * FROM posts WHERE post_id = :id";
                        $stmti = $pdo->prepare($sqli);
                        $stmti->execute([
                            ':id' => $postid
                        ]);

                        while ($p = $stmti->fetch(PDO::FETCH_ASSOC)) {
                            $post_image = $p['post_image'];
                        }
                    }
                    if (empty($post_title) || empty($post_cat_id) || empty($post_status) || empty($post_content)) {
                        echo "<div class='alert alert-danger'>Field can't be empty!</div>";
                    } else {
                        $sql = "UPDATE posts SET post_title = :title, post_des = :post, post_image = :image, post_date = :date, post_author = :author, post_cat_id = :catid, post_status = :status WHERE post_id = :pid";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':title' => $post_title,
                            ':post' => $post_content,
                            ':image' => $post_image,
                            ':date' => $post_date,
                            ':author' => $post_author,
                            ':catid' => $post_cat_id,
                            ':status' => $post_status,
                            ':postid' => $postid
                        ]); 

                        header("Location: index.php");
                    }
                } 

            ?>

            <form method="POST" action="edit-post.php" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="edit-post-id" value="<?php echo $post_id; ?>">
                    <label for="post-title">Post Title</label>
                    <input name="post-title" type="text" class="form-control" id="post-title" placeholder="Enter post title" value="<?php echo $post_title; ?>">
                </div>
                <div class="form-group">
                    <label for="category">Select Category</label>
                    <select name="cat-id" class="form-control" id="category">
                        <?php

                            /* Informa o nível dos erros que serão exibidos */
                            error_reporting(E_ALL);
                            /* Habilita a exibição de erros */
                            ini_set("display_errors", 1);
 
                            $sqlc = 'SELECT * FROM  categories';
                            $stmtc = $pdo->prepare($sqlc);
                            $stmtc->execute();
                            while ($category = $stmtc->fetch(PDO::FETCH_ASSOC)) {
                                $cat_id = $category['cat_id'];
                                $cat_title = $category['cat_title']; ?>
                                <option value="<?php echo $post_cat_id; ?>" <?php echo $cat_id == $post_cat_id?'selected':'' ?>><?php echo $cat_title; ?></option>
                            <?php } 

                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category">Post Status</label>
                    <select name="post-status" class="form-control" id="category">
                        <option <?php echo $post_status == 'Published'?'selected':'' ?>>Published</option>
                        <option <?php echo $post_status == 'Draft'?'selected':'' ?>>Draft</option>
                    </select>
                </div>
                <div class="form-group">
                    <img src="../img/<?php echo $post_image; ?>" style="width: 50px;height: 50px;">
                    <label for="post-image">Upload post image</label>
                    <input name="image" type="file" class="form-control-file" id="post-image">
                </div>
                <div class="form-group">
                    <label for="post-content">Post Content</label>
                    <textarea name="post-content" class="form-control" id="post-content" rows="6" cols="30" placeholder="Your post content"><?php echo $post_content; ?></textarea>
                </div>
                <button name="update-post" type="submit" class="btn btn-primary">Submit</button>
            </form>
        </section>

    </div>
<?php require_once('./includes/footer.php'); ?>