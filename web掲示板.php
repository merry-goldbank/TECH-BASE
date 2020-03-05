<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$db_password = 'パスワード';
$pdo = new PDO($dsn, $user, $db_password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
$db_name = 'tbtest3';

$sql = "CREATE TABLE IF NOT EXISTS tbtest3"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "created_datetime DATETIME,"
	. "toko_pass char(10)"
	.");";
$stmt = $pdo->query($sql);

if (isset($_POST['name']) && isset($_POST['comment']) && isset($_POST['toko_pass'])) {
    $toko_check = $_POST['toko_check'];

    if (!empty($toko_check)) {   //投稿が新規か編集かチェック
        $id = $toko_check; //変更する投稿番号
	    $name = $_POST['name'];
        $comment = $_POST['comment'];
        $toko_pass = $_POST['toko_pass'];

        $toko_pass_check = 0;

        $sql = 'SELECT * FROM tbtest3';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();

        foreach ($results as $row) {
            if ($row['toko_pass'] == $toko_pass) {
                $sql = 'update tbtest3 set name=:name,comment=:comment where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $toko_pass_check = 0;

                break;
            } else {
                $toko_pass_check = 1;
            }
        }
        
    } else {
        $sql = $pdo -> prepare("INSERT INTO tbtest3 (name, comment, created_datetime, toko_pass) VALUES (:name, :comment, :created_datetime, :toko_pass)");
	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':created_datetime', $date, PDO::PARAM_STR);
        $sql -> bindParam(':toko_pass', $toko_pass, PDO::PARAM_STR);

        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $date = date("Y-m-d H:i:s");
        $toko_pass = $_POST['toko_pass'];
        $sql -> execute();

    }

} elseif (isset($_POST['sakujo']) && isset($_POST['sakujo_pass'])) {
    $sakujo_id = $_POST['sakujo'];
    $sakujo_pass = $_POST['sakujo_pass'];

    $sakujo_id_check = 0;
    $sakujo_pass_check = 0;

    if (!empty($sakujo_id) && !empty($sakujo_pass)) {
        $sql = 'SELECT * FROM tbtest3';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
	    foreach ($results as $row){
		    
            if ($row['id'] == $sakujo_id) {

                if ($row['toko_pass'] == $sakujo_pass) {
                    $sql = 'delete from tbtest3 where id=:id';
	                $stmt = $pdo->prepare($sql);
	                $stmt->bindParam(':id', $sakujo_id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $sakujo_id_check = 0;
                    break;
                } else {
                    $sakujo_pass_check = 1;
                    break;
                }
                
            } else {
                $sakujo_id_check = 1;
            }
	    }
        
    }
} elseif (isset($_POST['henshu']) && isset($_POST['henshu_pass'])) {
    $henshu_id = $_POST['henshu'];
    $henshu_pass = $_POST['henshu_pass'];

    $henshu_id_check = 0;
    $henshu_pass_check = 0;

    if (!empty($henshu_id) && !empty($henshu_pass)) {
        $sql = 'SELECT * FROM tbtest3';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();

        foreach ($results as $row) {

            if ($row['id'] == $henshu_id) {    //投稿番号が一致したら

                if ($row['toko_pass'] == $henshu_pass) {    //かつパスワードが一致したら
                    $henshu_name = $row['name'];
                    $henshu_comment = $row['comment'];

                    $henshu_id_check = 0;
                    break;
                } else {
                    $henshu_pass_check = 1;
                    break;
                }
            } else {
                $henshu_id_check = 1;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>これはHTMLの練習場です</title>
        <meta charset = "utf-8">
    </head>

<body>
    <h1>ロード・トゥ・内定</h1>
    <hr>
    <h2>～御社が第一志望です～</h2>
    <br>
    <h4>【　投稿フォーム　】</h4>
    <form method = "POST" action = "#">
    <table border="1">
         <tr>
            <td>名前</td>
            <td><input type = "text" name = "name" value = "<?php if (!empty($henshu_name)) { echo $henshu_name; } ?>">
            <td>コメント</td>
            <td><td><input type = "text" name = "comment" value = "<?php if (!empty($henshu_comment)) { echo $henshu_comment; } ?>">
            <td>パスワード</td>
            <td><td><input type = "password" name = "toko_pass" >
            <td><td><input type = "hidden" name = "toko_check" value = "<?php if (!empty($henshu_name) && !empty($henshu_comment)) { echo $henshu_id; } ?>">
            <td colspan="2" align="center">
               <input type = "submit" name = "btn" value = "送信">
        </tr>
      </table>
    </form>
    <br>
    <h4>【　削除フォーム　】</h4>
    <form method = "POST" action = "#">
    <table border="1">
         <tr>
            <td>投稿番号</td>
            <td><td><input type = "text" name = "sakujo">
            <td>パスワード</td>
            <td><td><input type = "password" name = "sakujo_pass">
            <td colspan="2" align="center">
               <input type = "submit" name = "btn" value = "送信">
        </tr>
      </table>
    </form>
    <br>
    <h4>【　編集番号指定フォーム　】</h4>
    <form method = "POST" action = "#">
    <table border="1">
         <tr>
            <td>投稿番号</td>
            <td><td><input type = "text" name = "henshu">
            <td>パスワード</td>
            <td><td><input type = "password" name = "henshu_pass">
            <td colspan="2" align="center">
               <input type = "submit" name = "btn" value = "送信">
        </tr>
      </table>
    </form>

    <hr>

    <h4>
    <?php
        echo '--------------------------------------------';
        echo '<br>';

        if (!empty($sakujo_id) && empty($sakujo_pass)) {
            echo '削除対象のパスワードを入力してください';
            echo '<br>';
        } elseif (empty($sakujo_id) && !empty($sakujo_pass)) {
            echo '削除対象の投稿番号を入力してください';
            echo '<br>';
        } elseif (!empty($sakujo_id) && !empty($sakujo_pass) && $sakujo_pass_check == 1) {
            echo '削除対象のパスワードに誤りがあります';
            echo '<br>';
        } elseif (!empty($sakujo_id) && !empty($sakujo_pass) && $sakujo_id_check == 1) {
            echo '削除する投稿内容が存在しません';
            echo '<br>';
        } elseif (!empty($henshu_id) && empty($henshu_pass)) {
            echo '編集対象のパスワードを入力してください';
            echo '<br>';
        } elseif (empty($henshu_id) && !empty($henshu_pass)) {
            echo '編集対象の投稿番号を入力してください';
            echo '<br>';
        } elseif(!empty($henshu_id) && !empty($henshu_pass) && $henshu_pass_check == 1) {
            echo '編集対象のパスワードに誤りがあります';
            echo '<br>';
        } elseif(!empty($henshu_id) && !empty($henshu_pass) && $henshu_id_check == 1) {
            echo '編集する投稿内容が存在しません';
            echo '<br>';
        } elseif(!empty($name) && !empty($comment) && !empty($toko_pass) && !empty($toko_check) && $toko_pass_check == 1) {
            echo '編集対象のパスワードに誤りがあります';
            echo '<br>';
        } elseif (empty($name) && empty($comment) && empty($toko_pass)) {
            echo '名前を入力して「送信」をクリックしてください';
            echo '<br>';
        } elseif (empty($name) && !empty($comment) && empty($toko_pass)) {
            echo '名前を入力して「送信」をクリックしてください';
            echo '<br>';
        } elseif (empty($name) && empty($comment) && !empty($toko_pass)) {
            echo '名前を入力して「送信」をクリックしてください';
            echo '<br>';
        } elseif (empty($name) && !empty($comment) && !empty($toko_pass)) {
            echo '名前を入力して「送信」をクリックしてください';
            echo '<br>';
        } elseif (!empty($name) && empty($comment) && empty($toko_pass)) {
            echo 'コメントを入力して「送信」をクリックしてください';
            echo '<br>';
        } elseif (!empty($name) && empty($comment) && !empty($toko_pass)) {
            echo 'コメントを入力して「送信」をクリックしてください';
            echo '<br>';
        } elseif (!empty($name) && !empty($comment) && empty($toko_pass)) {
            echo 'パスワードを入力して「送信」をクリックしてください';
            echo '<br>';
        }

        echo '--------------------------------------------';
        echo '<br>';

        echo '<hr>';

        $sql = 'SELECT * FROM tbtest3';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].', ';
            echo $row['name'].', ';
            echo '「'.$row['comment'].'」, ';
            echo $row['created_datetime'].'<br>';
            echo "<hr>";
        }
    ?>
    </h4>

</body>
</html>