<?php
/****************************************/
/* 登録日別一覧ページ                   */
/****************************************/

  //共通データをインクルードします
  require_once("photolibini.php");

  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);

  //登録日付を集計するSQLを組み立てます
  $sql = "SELECT regdate, Count(bukkenid) AS cnt
          FROM baibaibukken
          GROUP BY regdate
          ORDER BY regdate DESC";

  //結果セットを取得します
  $rst = mysql_query($sql, $con);

  //ページ本文を組み立てます
  $body = "現在、次の日付の物件が登録されています。表示したい日付をクリックしてください。
          <BR><BR>
          <UL type='circle'>";

  //結果セットからデータをループで読み込みます
  while ($col = mysql_fetch_array($rst)) {
    $body .= "<LI><A href='search.php?regdate=$col[regdate]'>" .
              substr($col[regdate], 0, 4) . "年" .
              substr($col[regdate], 5, 2) . "月" .
              substr($col[regdate], 8, 2) . "日</A> " .
              "（$col[cnt]件）";
  }

  $body .= "</UL>";

  //結果セットを破棄します
  mysql_free_result($rst);
  //MySQLとの接続を解除します
  $con = mysql_close($con);


  //ページヘッダを出力します
  print htmlheader("登録日別一覧");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();

?>
