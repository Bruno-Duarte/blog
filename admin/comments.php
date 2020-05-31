<?php require_once('./includes/header.php'); ?>
<?php 

  if (!isset($_COOKIE['_ua_'])) {
    header("Location: sign-in.php");
  }

?>
    <div class="fluid-container">
      <?php require_once('./includes/navigation.php'); ?>
      
      <section id="main" class="mx-lg-5 mx-md-2 mx-sm-2">
        <div class="d-flex flex-row justify-content-between">
            <h2 class="my-3">All Comments</h2>
        </div>
        
        <table class="table">
            <thead class="thead-dark">
                <tr>
                <th scope="col">ID</th>
                <th scope="col">User name</th>
                <th scope="col">Comment</th>
                <th scope="col" class="d-none d-md-table-cell">In response to</th>
                <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php 

                    $sql = "SELECT * FROM comments WHERE comment_post_id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':id' => $_GET['id']
                    ]);
                    $count = $stmt->rowCount();

                    if ($count == 0) {
                        echo "No comments";
                        exit();
                    }

                    while ($com = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $c_id = $com['comment_id'];
                        $c_content = $com['comment_des'];
                        $c_author = $com['comment_author']; ?>

                        <tr>
                            <td><?php echo $c_id; ?></td>
                            <td><?php echo $c_author; ?></td>
                            <td><?php echo $c_content; ?></td>
                            <td class="d-none d-md-table-cell">
                                <a href="../single.php?id=<?php echo $_GET['id']; ?>">
                                    <?php  

                                        $sqlp = "SELECT * FROM posts WHERE post_id = :id";
                                        $stmtp = $pdo->prepare($sqlp);
                                        $stmtp->execute([
                                            ':id' => $_GET['id']
                                        ]);

                                        while ($post = $stmtp->fetch(PDO::FETCH_ASSOC)) {
                                            $post_title = $post['post_title'];
                                        }

                                        echo $post_title;

                                    ?>        
                                </a>
                            </td>
                            <td>
                                <form action="comments.php?id=<?php echo $_GET['id']; ?>" method="POST">
                                    <input type="hidden" name="val" value="<?php echo $c_id; ?>">
                                    <input type="submit" name="delete" value="Delete" class="btn btn-link">
                                </form>               
                            </td>
                        </tr>
                    
                    <?php }

                ?>
            </tbody>
        </table>

        <?php 

            if (isset($_POST['delete'])) {
                $cid = $_POST['val'];
                
                // delete the comments in comments table
                $sqld = "DELETE FROM comments WHERE comment_id = :id";
                $stmtd = $pdo->prepare($sqld);
                $stmtd->execute([
                    ':id' => $cid
                ]);

                // update post_comment in post table
                $sqlu = "UPDATE posts SET post_comment = post_comment - 1 WHERE post_id = :pid";
                $stmtu = $pdo->prepare($sqlu);
                $stmtu->execute([
                    ':pid' => $_GET['id']
                ]);

                header("Location: comments.php?id={$_GET['id']}");

            }

        ?>
    
      </section>

      <ul class="pagination px-lg-5">
        <li class="page-item disabled">
          <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item active">
          <a class="page-link" href="#">2</a>
        </li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
          <a class="page-link" href="#">Next</a>
        </li>
      </ul>

    </div>

<?php require_once('./includes/footer.php'); ?>