<?php
/****************************************/
/* 物件の編集実行ページ                 */
/****************************************/

  //共通データをインクルードします
  require("photolibini.php");

  //前のページからデータを受け取り
  $bukkenid    = $_POST[bukkenid];
  $imagefile  = $_POST[imagefile];
  $imagefile1 = $_POST[imagefile1];
  $imagefile2 = $_POST[imagefile2];
  $imagefile3 = $_POST[imagefile3];
  $oldfile    = $_POST[oldfile];
  $oldfile1   = $_POST[oldfile1];
  $oldfile2   = $_POST[oldfile2];
  $oldfile3   = $_POST[oldfile3];
  $categoryid = $_POST[categoryid];
  $categoryid1 = $_POST[categoryid1];
  $jyusyo     = $_POST[jyusyo];
  $moyorieki  = $_POST[moyorieki];
  $sikiti     = $_POST[sikiti];
  $tatemono   = $_POST[tatemono];
  $madori     = $_POST[madori];
  $kouzou     = $_POST[kouzou];
  $sale       = $_POST[sale];
  $sonota     = $_POST[sonota];
  $comment    = $_POST[comment];
  $proc       = $_POST[proc];
  $cancel     = $_POST[cancel];
  $torihiki   = $_POST[torihiki];
  $hikiwatasi = $_POST[hikiwatasi];
  $genjyou        = $_POST[genjyou];
  $kenri          = $_POST[kenri];
  $youto          = $_POST[youto];
  $kuruma         = $_POST[kuruma];
  $kousai         = $_POST[kousai];
  $kansei         = $_POST[kansei];
  $kakunin        = $_POST[kakunin];
  $address        = $_POST[address];
  $kenpeiritu     = $_POST[kenpeiritu];
  $yousekiritu    = $_POST[yousekiritu];
  $miti           = $_POST[miti];

  if (isset($cancel)) {
    //キャンセルボタンが押されたとき
    //アップロードされた写真のファイルの実体を削除
    if (strlen($imagefile) > 0 and strlen($imagefile1) > 0 and strlen($imagefile2) > 0 and strlen($imagefile3) > 0) {
      unlink("$PHOTOTMP$imagefile" );
      unlink("$PHOTOTMP$imagefile1");
      unlink("$PHOTOTMP$imagefile2");
      unlink("$PHOTOTMP$imagefile3");
    }
    
    //写真のメンテナンスページへリダイレクト
    header("Location: photomnt.php");
    exit();
  }
  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);

  //既存データの更新で呼ばれたとき
  if ($proc == "edit") {
    //コメントに含まれるBRタグを取り除きます
    $comment = str_replace("<br />", "", $comment);
    $sonota  = str_replace("<br />", "", $sonota);
 
    //既存レコードを更新するSQL文を組み立てます
    if (strlen($imagefile) > 0 and strlen($imagefile1) > 0 and strlen($imagefile2) > 0 and strlen($imagefile3) > 0) {


      //すべてのファイルを変更するとき

      $sql = "UPDATE baibaibukken SET photofilename   = '$imagefile',
                                      photofilename1  = '$imagefile1',
                                      photofilename2  = '$imagefile2',
                                      madoriimagefile = '$imgaefile3',
                                      jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()

                                   WHERE bukkenid = $bukkenid";
        
        

      //写真のファイルを一時保存先から最終保存先に移動
      rename("$PHOTOTMP$imagefile",  "$PHOTODIR$imagefile");
      $oldfile = dfirst("photofilename", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile");

      rename("$PHOTOTMP$imagefile1", "$PHOTODIR$imagefile1");
      $oldfile1 = dfirst("photofilename1", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile1");

      rename("$PHOTOTMP$imagefile2", "$PHOTODIR$imagefile2");
      $oldfile2 = dfirst("photofilename2", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile2");

      rename("$PHOTOTMP$imagefile3", "$PHOTODIR$imagefile3");
      $oldfile3 = dfirst("madoriimagefile", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile3");
     }

        if (strlen($imagefile) > 0 and strlen($imagefile1) > 0 and strlen($imagefile2) ==0 and strlen($imagefile3) > 0)  {

        $sql = "UPDATE baibaibukken SET photofilename   = '$imagefile',
                                        photofilename1  = '$imagefile1',
                                        madoriimagefile = '$imagefile3',
                                      jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()


                                   WHERE bukkenid = $bukkenid";

      rename("$PHOTOTMP$imagefile",  "$PHOTODIR$imagefile");
      $oldfile = dfirst("photofilename", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile");
      rename("$PHOTOTMP$imagefile1", "$PHOTODIR$imagefile1");
      $oldfile1 = dfirst("photofilename1", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile1");
      rename("$PHOTOTMP$imagefile3", "$PHOTODIR$imagefile3");
      $oldfile3 = dfirst("madoriimagefile", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile3");

     }

        if (strlen($imagefile) > 0 and strlen($imagefile1) > 0 and strlen($imagefile2) > 0 and strlen($imagefile3) == 0)  {

        $sql = "UPDATE baibaibukken SET photofilename   = '$imagefile',
                                        photofilename1  = '$imagefile1',
                                        photofilename2  = '$imagefile2',
                                      jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()

                                   WHERE bukkenid = $bukkenid";

      rename("$PHOTOTMP$imagefile",  "$PHOTODIR$imagefile");
      $oldfile = dfirst("photofilename", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile");
      rename("$PHOTOTMP$imagefile1", "$PHOTODIR$imagefile1");
      $oldfile1 = dfirst("photofilename1", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile1");
      rename("$PHOTOTMP$imagefile2", "$PHOTODIR$imagefile2");
      $oldfile2 = dfirst("photofilename2", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile2");

     }

      if (strlen($imagefile) == 0 and strlen($imagefile1) > 0 and strlen($imagefile2) > 0 and strlen($imagefile3) > 0)  {

        $sql = "UPDATE baibaibukken SET photofilename  = '$imagefile1',
                                    photofilename   = '$imagefile2',
                                    madoriimagefile = '$imagefile3',
                                      jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()



                                   WHERE bukkenid = $bukkenid";

      rename("$PHOTOTMP$imagefile1",  "$PHOTODIR$imagefile1");
      $oldfile = dfirst("photofilename1", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile1");
      rename("$PHOTOTMP$imagefile2", "$PHOTODIR$imagefile2");
      $oldfile1 = dfirst("photofilename2", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile2");
      rename("$PHOTOTMP$imagefile3", "$PHOTODIR$imagefile3");
      $oldfile3 = dfirst("madoriimagefile", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile3");
     }

       if (strlen($imagefile) > 0 and strlen($imagefile1) == 0 and strlen($imagefile2) == 0 and strlen($imagefile3) > 0)  {
      $sql = "UPDATE baibaibukken SET photofilename   = '$imagefile',
                                      madoriimagefile = '$imagefile3',
                                      jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()


                                   WHERE bukkenid = $bukkenid";

      rename("$PHOTOTMP$imagefile", "$PHOTODIR$imagefile");
      $oldfile1 = dfirst("photofilename", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile");
      rename("$PHOTOTMP$imagefile3", "$PHOTODIR$imagefile3");
      $oldfile3 = dfirst("madoriimagefile", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile3");
    }

     if (strlen($imagefile) == 0 and strlen($imagefile1) > 0 and strlen($imagefile2) == 0 and strlen($imagefile3) > 0)        {

     $sql = "UPDATE baibaibukken SET photofilename1  = '$imagefile1',
                                     madoriimagefile = '$imagefile3',
                                      jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()


                                   WHERE bukkenid = $bukkenid";



      rename("$PHOTOTMP$imagefile1", "$PHOTODIR$imagefile1");
      $oldfile2 = dfirst("photofilename1", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile1");
      rename("$PHOTOTMP$imagefile3", "$PHOTODIR$imagefile3");
      $oldfile3 = dfirst("madoriimagefile", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile3");

     }

      if (strlen($imagefile) == 0 and strlen($imagefile1) == 0 and strlen($imagefile2) > 0 and strlen($imagefile3) == 0)        {

     $sql = "UPDATE baibaibukken SET photofilename2  = '$imagefile2',
                                      jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()


                                   WHERE bukkenid = $bukkenid";



      rename("$PHOTOTMP$imagefile2", "$PHOTODIR$imagefile2");
      $oldfile2 = dfirst("photofilename2", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile2");

    }

    if (strlen($imagefile) == 0 and strlen($imagefile1) == 0 and strlen($imagefile2) == 0 and strlen($imagefile3) > 0)  {
      $sql = "UPDATE baibaibukken SET madoriimagefile = '$imagefile3',
                                      jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()


                                   WHERE bukkenid = $bukkenid";

      rename("$PHOTOTMP$imagefile3", "$PHOTODIR$imagefile3");
      $oldfile3 = dfirst("photofilename3", "baibaibukken", "bukkenid=$bukkenid",False);
      @unlink("$PHOTODIR$oldfile3");
   }
    if (strlen($imagefile) == 0 and strlen($imagefile1) == 0 and strlen($imagefile2) == 0 and strlen($imagefile3) == 0) {
      //写真のファイルは変更しないとき
      $sql = "UPDATE baibaibukken SET jyusyo         = '$jyusyo',
                                      moyorieki      = '$moyorieki',
                                      sikiti         = '$sikiti',
                                      tatemono       = '$tatemono',
                                      kouzou         = '$kouzou',
                                      sale           = '$sale',
                                      sonota         = '$sonota',
                                      madori         = '$madori',
                                      categoryid      = $categoryid,
                                      categoryid1     = $categoryid1,
                                      kakunin        = '$kakunin',
                                      kansei         = '$kansei',
                                      kousai         = '$kousai',
                                      kenri          = '$kenri',
                                      kuruma         = '$kuruma',
                                      kenpeiritu     = '$kenpeiritu',
                                      yousekiritu    = '$yousekiritu',
                                      miti           = '$miti',
                                      genjyou        = '$genjyou',
                                      hikiwatasi     = '$hikiwatasi',
                                      torihiki       = '$torihiki',
                                      address        = '$address',
                                      youto          = '$youto',
                                      comment        = '$comment',
                                      regdate        = CURDATE()


                                   WHERE bukkenid = $bukkenid";
    }

    
    //SQL文を発行します
    $rst = mysql_query($sql, $con);
    if ($rst) {
      //成功したとき
      $body = "更新を完了しました！";
    }
    else {
      //失敗したとき
      $body = "更新に失敗しました！";
    }
   }
  //既存データの削除で呼ばれたとき
  if ($proc == "del") {
    //写真ファイル名を取得して実体を削除します
    $oldfile  = dfirst("photofilename", "baibaibukken", "bukkenid=$bukkenid", False);
    @unlink("$PHOTODIR$oldfile");
    $oldfile1 = dfirst("photofilename1", "baibaibukken", "bukkenid=$bukkenid", False);
    @unlink("$PHOTODIR$oldfile1");
    $oldfile2 = dfirst("photofilename2", "baibaibukken", "bukkenid=$bukkenid", False);
    @unlink("$PHOTODIR$oldfile2");
    $oldfile3 = dfirst("madoriimagefile", "baibaibukken", "bukkenid=$bukkenid", False);
    @unlink("$PHOTODIR$oldfile3");
  
 
    //既存レコードを削除するSQL文を組み立てます
    $sql = "DELETE FROM baibaibukken WHERE bukkenid = $bukkenid";

    //SQL文を発行します
    $rst = mysql_query($sql, $con);
    if ($rst) {
      //成功したときはメンテナンスページへリダイレクト
      header("Location: photomnt.php");
      exit();
    }
    else {
      //失敗したとき
      $body = "削除に失敗しました！";
    }
   }
  

  $body .= "<BR><BR>
            <INPUT type='button' value='次の物件を編集' onclick='window.location=\"photomnt.php\"'>
            <INPUT type='button' value='ホームへ戻る' onclick='window.location=\"tourokukannri.htm\"'>";

  //MySQLとの接続を解除します
  $con = mysql_close($con);


  //ページヘッダを出力します
  print htmlheader("写真の編集実行");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();


?>
