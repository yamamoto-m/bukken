<?php
/****************************************/
/* 共通インクルードファイル             */
/****************************************/


  $DBSERVER   = "localhost";    //MySQLサーバー名
  $DBUSER     = "root";         //ログインユーザー名
  $DBPASSWORD = "0480311815";   //パスワード
  $DBNAME     = "photolibdb";   //データベース名

  $PHOTODIR   = "photo/";       //写真ファイルの最終保存先パス
  $PHOTOTMP   = "phototmp/";    //写真ファイルの一時保存先パス

  $ADMSESS    = "sslogined";    //管理者ログインで使うセッション変数名


function htmlheader($pagetitle) {
//各ページのヘッダ部のHTMLを組み立てる

  $strret = " <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
              <HTML>
              <HEAD>
              <META http-equiv='Content-Type' content='text/html; charset=EUC-JP'>
              <META http-equiv='Content-Style-Type' content='text/css'>
              <TITLE>物件databases - $pagetitle</TITLE>
              <LINK rel='stylesheet' href='photolib.css' type='text/css'>
              </HEAD>
              <BODY>
              <DIV class='maintitle'>物件databases</DIV>
              <DIV class='tohomelink'><A href=http://localhost/test/index.htm>HOME</A></DIV>
              <DIV class='pagetitle'>$pagetitle</DIV>
              <DIV class='maincontents'>
              <BR>";

  return $strret;

}

function htmlfooter() {
//各ページのフッタ部のHTMLを組み立てる

  $strret = "<BR>
              </DIV>
              </BODY>
              </HTML>";

  return $strret;

}

function dfirst($fldname, $tblname, $criteria, $closeflg) {
//指定テーブルからcriteraに一致する先頭レコードを抽出しその指定フィールドの値を返す

  //当関数外の変数を参照します
  global $DBSERVER;
  global $DBUSER;
  global $DBNAME;
  global $DBPASSWORD;

  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);

  //引数に応じてSQL文を組み立てます
  if (strlen($criteria) > 0) {
    $sql = "SELECT $fldname FROM $tblname WHERE $criteria";
  }
  else {
    $sql = "SELECT $fldname FROM $tblname";
  }

  //結果セットを取得します
  $rst = mysql_query($sql, $con);
  $col = mysql_fetch_array($rst);

  //最初のレコードの指定フィールドの値を取り出します
  $strret = $col[$fldname];

  //結果セットを破棄します
  mysql_free_result($rst);

  if ($closeflg) {
    //MySQLとの接続を解除します
    $con = mysql_close($con);
  }

  return $strret;

}

function debugprint($data) {
//デバッグ用HTML出力
  
  print "<font color='red'>" . $data . "</font><br>";
  
  }

?>
