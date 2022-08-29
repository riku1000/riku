<!DOCTYPE HTML>

<html lang="ja">
    
<head>
    
    <meta charset = "UTF-8">
    <title> mission_5-1 </title>
    
</head>

<body>

    <form action = "" method = "post">
        
        <!--投稿・書き込みフォーム-->
        <input type = "text" name = "name" placeholder = "名前">
        <input type = "text" name = "comment" placeholder = "コメント">
        <input type = "text" name = "pass" placeholder = "パスワード">
        <input type = "submit" name = "send" value = "投稿"><br><br>
        
        <!--削除フォーム-->
        <input type = "number" name = "delete_num" placeholder = "削除対象番号">
        <input type = "text" name = "delete_pass" placeholder = "パスワード">
        <input type = "submit" name = "delete" value = "削除"><br>
        
        <!--編集フォーム-->
        <input type = "number" name = "edit_num" placeholder = "編集対象番号">
        <input type = "text" name = "edit_name" placeholder = "名前">
        <input type = "text" name = "edit_comment" placeholder = "コメント">
        <input type = "text" name = "edit_pass" placeholder = "パスワード">
        <input type = "submit" name = "edit" value = "編集"><br><br>
        
    </form>

    <?php
    
        //データベース接続
        $dsn = 'mysql:dbname=tb240184db;host=localhost';
        $user = 'tb-240184';
        $password = 'g9HTdABsry';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //CREATE文：データベース内にテーブルを作成
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "pass TEXT"
        .");";
        $stmt = $pdo->query($sql);
        
        //$_POST：フォームから受け取り
        $name = $_POST["name"] ?? ""; //NUll合体演算子：$strがNullの場合，??の右に指定した値が返される
        $comment = $_POST["comment"] ?? ""; //NUll合体演算子：$strがNullの場合，??の右に指定した値が返される
        $pass = $_POST["pass"] ?? "";
        $delete_num = $_POST["delete_num"] ?? ""; //NUll合体演算子：$strがNullの場合，??の右に指定した値が返される
        $delete_pass = $_POST["delete_pass"] ?? "";
        $edit_num = $_POST["edit_num"] ?? "";
        $edit_name = $_POST["edit_name"] ?? "";
        $edit_comment = $_POST["edit_comment"] ?? "";
        $edit_pass = $_POST["edit_pass"] ?? "";
        
        $date = date("Y/m/d H:i:s");
        
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
        if (!empty($name) && !empty($comment)) {
            
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            //$name = '一郎';
            //$comment = 'あああ'; //好きな名前、好きな言葉は自分で決めること
            $sql -> execute();
            //bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなります。最適なものを適宜決めよう。
        
            //foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                //echo $row['id'].',';
                //echo $row['name'].',';
                //echo $row['comment'].',';
                //echo $row['pass'].'<br>';
            //echo "<hr>";
            //}
        }
        
        foreach ($results as $row){
        if ($row['id'] == $delete_num && $row['pass'] == $delete_pass) {
        $id = $delete_num;
        $sql = 'delete from tbtest where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        }
        }
        
        foreach ($results as $row){
        if ($row['id'] == $edit_num && $row['pass'] == $edit_pass) {
        $id = $edit_num;
        if (!empty($edit_name) && !empty($edit_comment)) {
        $name = $edit_name;
        $comment = $edit_comment;
        $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        }
        }
        }
        
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',　';
            echo $row['name'].',　';
            echo $row['comment'].',　';
            echo $row['date'].',　';
            echo 'パスワード：'.$row['pass'].'<br>';
        echo "<hr>";
        }
    ?>
    
</body>
    
</html>