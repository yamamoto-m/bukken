<?php
/****************************************/
/* カテゴリ別一覧ページ                 */
/****************************************/

  //共通データをインクルードします
  require_once("photolibini.php");

  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);

  //カテゴリを集計するSQLを組み立てます
  $sql = "SELECT baibaibukken.categoryid, categoryname, Count(bukkenid) AS cnt
          FROM baibaibukken
          INNER JOIN tblcategory ON baibaibukken.categoryid = tblcategory.categoryid
          GROUP BY baibaibukken.categoryid
          ORDER BY baibaibukken.categoryid";

  //結果セットを取得します
  $rst = mysql_query($sql, $con);

  //ページ本文を組み立てます
  $body = "現在、次のカテゴリの写真が登録されています。表示したいカテゴリをクリックしてください。
          <BR><BR>
          <UL type='circle'>";

  //結果セットからデータをループで読み込みます
  while ($col = mysql_fetch_array($rst)) {
    $body .= "<LI><A href='search.php?categid=$col[categoryid]'>
              $col[categoryname]</A> " .
              "（$col[cnt]件）";
  }

  $body .= "</UL>";

  //結果セットを破棄します
  mysql_free_result($rst);
  //MySQLとの接続を解除します
  $con = mysql_close($con);


  //ページヘッダを出力します
  print htmlheader("カテゴリ別一覧");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();

?>
