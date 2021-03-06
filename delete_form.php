<?php 
session_start();
require('header.php');
require('connect.php');
require('function.php'); 

  // ログインしているか確認（セッション時間＝１時間）
  if (isset($_SESSION['user_id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $users = $pdo->prepare('SELECT * FROM users WHERE user_id=?');
    $users->execute(array($_SESSION['user_id']));
    $user = $users->fetch();
  } else {
    header('Location: login.php');
    exit();
  }

?>
    
    <div class="l-contents">
    <p><?php print h($user['name']); ?>さんでログイン中です。<span><a href="logout.php">ログアウトする</a></span></p>
      <article>
        
        <!-- ログインユーザーの商品情報を表示 -->
          <?php
          $items = $pdo->prepare('SELECT * FROM items WHERE user_id=? ORDER BY date');
          $items->execute(array($_SESSION['user_id']));
          $item = $users->fetch();
          ?>
           <table>
            <tr>
              <th>id</th>
              <th>品名</th>
              <th>数</th>
              <th>賞味期限</th>
              <th>店舗</th>
              <th></th>
              <th></th>
            </tr>
          <?php while ($item = $items->fetch()): ?>
          
            <?php        
              // 賞味期限のタイムスタンプを取得
              $timestamp_limit = strtotime($item['date']);
              // 今日のタイムスタンプを取得
              $timestamp_today = strtotime('now');                  
              // 経過日を取得して小数点切り捨て (1日 = 60秒 x 60分 x 24時間)
              $interval =  floor(($timestamp_limit - $timestamp_today) / (60 * 60 * 24)); 
            ?>
            
              <form method="post" action="delete.php">
              <tr>
                <td><?php if( !empty($item['id']) ){ print($item['id']); } ?></td>
                <td><input type="text" name="name" value="<?php if( !empty($item['name']) ){ print($item['name']); } ?>" disabled></td>
                <td><input type="text" name="quantity" value="<?php if( !empty($item['quantity']) ){ print($item['quantity']); } ?>" disabled></td>
                <td><input type="text" name="date" value="<?php if( !empty($item['date']) ){ print($item['date']); } ?>" disabled></td>
                <td><input type="text" name="store" value="<?php if( !empty($item['store']) ){ print($item['store']); } ?>" disabled></td>

                <td><a href="index.php"><button type="button" class="c-btn">取消</button></a></td>
                <td><button type="submit" class="c-btn c-btn--delete">削除</button></td>
                <input type="hidden" name="id" value="<?php print($item['id']) ?>">
                <input type="hidden" name="name" value="<?php print($item['name']) ?>">
                <input type="hidden" name="quantity" value="<?php print($item['quantity']) ?>">
                <input type="hidden" name="date" value="<?php print($item['date']) ?>">
                <input type="hidden" name="store" value="<?php print($item['store']) ?>">
              </tr>
            </form>
          <?php endwhile; ?>
          </table>
      </article>
    </div>

  </div>

</body>
</html>