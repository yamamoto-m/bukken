<?php
/****************************************/
/* カテゴリのメンテナンスページ         */
/****************************************/

  //共通データをインクルードします
  require_once("photolibini.php");

  //セッションを開始します
  session_start();

  //ログインチェック
  if (!session_is_registered($ADMSESS)) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    header("location: adminlogin.php?id=3");
    exit();
  }

  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);

  //自分自身から呼ばれたときは編集処理を行います
  $reccnt = $_POST[reccnt];
  if (isset($reccnt)) {
    //ループで押されたボタンを調べます
    for ($cnt = 0; $cnt < $reccnt; $cnt++) {
      //更新ボタンをチェックします
      $btnname = "btnedit" . $cnt;
      if (isset($_POST[$btnname])) {
        //更新用SQL文を組み立てます
        $sql = "UPDATE tblcategory
                SET categoryname = \"" . $_POST[categname][$cnt] . "\"
                WHERE categoryid = "   . $_POST[categid][$cnt];
        //SQL文を発行します
        mysql_query($sql, $con);
      }
      //削除ボタンをチェックします
      $btnname = "btndel" . $cnt;
      if (isset($_POST[$btnname])) {
        //削除用SQL文を組み立てます
        $sql = "DELETE FROM tblcategory
                WHERE categoryid = " . $_POST[categid][$cnt];
        //SQL文を発行します
        mysql_query($sql, $con);
        //baibaibukkenから物件ファイル名を取得して実体を削除します
        $sql = "SELECT photofilename FROM baibaibukken
                WHERE categoryid = " . $_POST[categid][$cnt];
        $rst = mysql_query($sql, $con);
        while($col = mysql_fetch_array($rst)) {
          unlink("$PHOTODIR$col[photofilename]");
　　　　　unlink("$PHOTODIR$col[photofilename1]");
　　　　　unlink("$PHOTODIR$col[photofilename2]");
　　　　　unlink("$PHOTODIR$col[madoriimagefile]");
        }
        mysql_free_result($rst);
        //baibaibukkenから当該カテゴリのレコードを削除します
        $sql = "DELETE FROM baibaibukken
                WHERE categoryid = " . $_POST[categid][$cnt];
        //SQL文を発行します
        mysql_query($sql, $con);
      }
    }
    //追加ボタンをチェックします
    $btnname = "btnaddnew";
    if (isset($_POST[$btnname])) {
      //追加用SQL文を組み立てます
      $sql = "INSERT INTO tblcategory
              (categoryname) VALUES (\"$_POST[newcateg]\")";
      //SQL文を発行します
      mysql_query($sql, $con);
    }
  }

  //全データを読み込むSQL文を組み立てます
  $sql = "SELECT * FROM tblcategory ORDER BY categoryid";

  //結果セットを取得します
  $rst = mysql_query($sql, $con);

  //ページ本文を組み立てます
  $body .= "<BR>
            <FORM action='$_SERVER[PHP_SELF]' method='POST'>
            <TABLE class='photolist'>
              <TR>
                <TH>カテゴリ名 <SPAN class='midfont'>50文字まで</SPAN></TH>
              </TR>";

  //結果セットからデータをループで読み込みます
  $cnt = 0;
  while($col = mysql_fetch_array($rst)) {
    //各レコード内容を表示する表を組み立てます
    $body .= "<TR>
                <TD>
                  <INPUT type='text'   name='categname[]' value=\"$col[categoryname]\" size='70'>
                  <INPUT type='submit' name='btnedit$cnt' value='更新'>
                  <INPUT type='submit' name='btndel$cnt'  value='削除' onClick='return confirm(\"削除していいですか？\");'>
                  <INPUT type='hidden' name='categid[]'   value='$col[categoryid]'>
                </TD>
              </TR>";
    $cnt++;
  }
  $body .= "<TR>
              <TD>新規カテゴリ<BR>
                <INPUT type='text'   name='newcateg'  size='70'>
                <INPUT type='submit' name='btnaddnew' value=' 追加 '>
              </TD>
            </TR>
          </TABLE>
          <INPUT type='hidden' name='reccnt' value='$cnt'>
          </FORM>";

  //結果セットを破棄します
  mysql_free_result($rst);
  //MySQLとの接続を解除します
  $con = mysql_close($con);


  //ページヘッダを出力します
  print htmlheader("カテゴリのメンテナンス");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();

?>
