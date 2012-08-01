<?php
/****************************************/
/* 物件の新規登録実行ページ             */
/****************************************/

  //共通データをインクルードします
  require_once("photolibini.php");

  //前のページからデータを受け取り
  $imagefile   = $_POST[imagefile];
  $imagefile1  = $_POST[imagefile1];
  $imagefile2  = $_POST[imagefile2];
  $imagefile3  = $_POST[imagefile3];
  $categoryid = $_POST[categoryid];
  $comment    = $_POST[comment];
  $jyusyo     = $_POST[jyusyo];
  $moyorieki  = $_POST[moyorieki];
  $sikiti     = $_POST[sikiti];
  $sonota     = $_POST[sonota];
  $tatemono   = $_POST[tatemono];
  $madori     = $_POST[madori];
  $sale            = $_POST[sale];
  $kouzou          = $_POST[kouzou];
  $torihiki        = $_POST[torihiki];
  $youto           = $_POST[youto];
  $genjyou         = $_POST[genjyou];
  $kuruma          = $_POST[kuruma];
  $kansei          = $_POST[kansei];
  $hikiwatasi      = $_POST[hikiwatasi];
  $kakunin         = $_POST[kakunin];
  $address         = $_POST[address];
  $kousai          = $_POST[kousai];
  $kenri           = $_POST[kenri];
  $yousekiritu     = $_POST[yousekiritu];
  $kenpeiritu      = $_POST[kenpeiritu];
  $miti            = $_POST[miti];
  $cancel          = $_POST[cancel];

  if (isset($cancel)) {
    //キャンセルボタンが押されたとき
    //アップロードされた写真のファイルの実体を削除
    unlink("$PHOTOTMP$imagefile");
    unlink("$PHOTOTMP$imagefile1");
    unlink("$PHOTOTMP$imagefile2");
    unlink("$PHOTOTMP$imagefile3");

    //物件の新規登録ページへリダイレクト
    header("Location: photonew.php");
    exit();
  }


  //コメントに含まれるBRタグを取り除きます
  $comment     = str_replace("<br />", "", $comment);
  $moyorieki   = str_replace("<br />", "", $moyorieki);
  $sonota      = str_replace("<br />", "", $sonota);
  $madori      = str_replace("<br />", "", $madori);
  $jyusyo      = str_replace("<br />", "", $jyusyo);
  $sikiti      = str_replace("<br />", "", $sikiti);
  $tatemono    = str_replace("<br />", "", $tatemono);
  $sale        = str_replace("<br />", "", $sale);
  $kouzou      = str_replace("<br />", "", $kouzou);
  $miti        = str_replace("<br />", "", $miti);
  $youto       = str_replace("<br />", "", $youto);
  $kousai      = str_replace("<br />", "", $youto);
  $kakunin     = str_replace("<br />", "", $kakunin);
  $kenpeiritu  = str_replace("<br />", "", $kenpeiritu);
  $yousekiritu = str_replace("<br />", "", $yousekiritu);

 //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);

  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");

  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);

  //レコード追加するSQL文を組み立てます
  $sql = "INSERT INTO baibaibukken
          (photofilename, photofilename1, photofilename2, madoriimagefile, jyusyo, moyorieki, sikiti, tatemono, madori, kouzou, sale, sonota, categoryid, kakunin, kansei, kousai, kenri, kuruma, kenpeiritu, yousekiritu, miti, genjyou, hikiwatasi, torihiki, address, youto, comment, regdate)
          VALUES
          (\"$imagefile\",\"$imagefile1\",\"$imagefile2\",\"$imagefile3\",\"$jyusyo\",\"$moyorieki\",\"$sikiti\",\"$tatemono\",\"$madori\",\"$kouzou\",\"$sale\",\"$sonota\", $categoryid, \"$kakunin\", \"$kansei\", \"$kousai\", \"$kenri\", \"$kuruma\", \"$kenpeiritu\", \"$yousekiritu\", \"$miti\", \"$genjyou\", \"$hikiwatasi\", \"$torihiki\", \"$address\", \"$youto\", \"$comment\", CURDATE())";

  //SQL文を発行します
  $rst = mysql_query($sql, $con);

  if ($rst) {
    //成功したとき
    $body = "登録を完了しました！";
    //写真のファイルを一時保存先から最終保存先に移動

    rename("$PHOTOTMP$imagefile", "$PHOTODIR$imagefile");
    rename("$PHOTOTMP$imagefile1", "$PHOTODIR$imagefile1");
    rename("$PHOTOTMP$imagefile2", "$PHOTODIR$imagefile2");
    rename("$PHOTOTMP$imagefile3", "$PHOTODIR$imagefile3");
 }
  
  else {
    //失敗したとき
    $body = "登録に失敗しました！";
    //アップロードされた写真のファイルの実体を削除
    unlink("$PHOTOTMP$imagefile");
    unlink("$PHOTOTMP$imagefile1");
    unlink("$PHOTOTMP$imagefile2");
    unlink("$PHOTOTMP$imagefile3");
  }
  $body .= "<BR><BR>
            <INPUT type='button' value='次の物件を登録' onclick='window.location=\"photonew.php\"'>
            <INPUT type='button' value='ホームへ戻る' onclick='window.location=\"tourokukannri.htm\"'>";

  //MySQLとの接続を解除します
  $con = mysql_close($con);


  //ページヘッダを出力します
  print htmlheader("物件の新規登録実行");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();

?>
