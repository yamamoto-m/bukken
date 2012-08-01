<?php
/****************************************/
/* 物件の編集確認ページ                 */
/****************************************/

  //共通データをインクルードします
  require_once("photolibini.php");

  

  //前のページからデータを受け取り
  $bukkenid        = $_POST[bukkenid];
  $uploadfile      = $_FILES[uploadfile][tmp_name];
  $uploadfile_name = $_FILES[uploadfile][name];
  $uploadfile_size = $_FILES[uploadfile][size];
  $uploadfile1     = $_FILES[uploadfile1][tmp_name];
  $uploadfile_name1= $_FILES[uploadfile1][name];
  $uploadfile_size1= $_FILES[uploadfile1][size];
  $uploadfile2     = $_FILES[uploadfile2][tmp_name];
  $uploadfile_name2= $_FILES[uploadfile2][name];
  $uploadfile_size2= $_FILES[uploadfile2][size];
  $uploadfile3     = $_FILES[uploadfile3][tmp_name];
  $uploadfile_name3= $_FILES[uploadfile3][name];
  $uploadfile_size3= $_FILES[uploadfile3][size];
  $categoryid      = $_POST[categoryid];
  $categoryid1     = $_POST[categoryid1];
  $jyusyo          = $_POST[jyusyo];
  $moyorieki       = $_POST[moyorieki];
  $sikiti          = $_POST[sikiti];
  $oldsikiti       = $_POST[oldsikiti];
  $tatemono        = $_POST[tatemono];
  $madori          = $_POST[madori];
  $kouzou          = $_POST[kouzou];
  $sale            = $_POST[sale];
  $sonota          = $_POST[sonota];
  $comment         = $_POST[comment];
  $oldfile         = $_POST[oldfile];
  $oldfile1        = $_POST[oldfile1];
  $oldfile2        = $_POST[oldfile2];
  $oldfile3        = $_POST[oldfile3];
  $regdate         = $_POST[regdate];
  $torihiki       = $_POST[torihiki];
  $hikiwatasi     = $_POST[hikiwatasi];
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
  $genjyou        = $_POST[genjyou];
  $kakunin        = $_POST[kakunin];
  


  //エスケープ文字の除去とタグの無効化を行ないます
  $comment = htmlspecialchars(stripcslashes($comment));
  $sonota  = htmlspecialchars(stripcslashes($sonota));
  //半角カタカナを全角カタカナに変換します
  $comment = mb_convert_kana($comment, "KV", "utf-8");
  $sonota  = mb_convert_kana($sonota, "KV", "utf-8");

  //各入力データのチェックを行ないます
  $errmsg = "";
  if ($categoryid == 0) {
    $errmsg .= "カテゴリが選択されていません。<BR>";
  }
  if (mb_strlen($comment, "utf-8") > 255) {
    $errmsg .= "コメントの文字数が多すぎます。<BR>";
  }
  if (mb_strlen($sonota, "utf-8") > 50)  {
    $errmsg .= "設備説明文字数が多すぎます。 <BR>";
  }
  //改行コードをBRタグに置換します
  $sonota  = nl2br($sonota);
  $comment = nl2br($comment);
  

  //写真ファイルのアップロード処理
  if(strlen($uploadfile) > 0 ) {
    //アップロードされたテンポラリファイルの情報を取得します
    $fileinfo = pathinfo($uploadfile_name);
    $fileext  = strtoupper($fileinfo[extension]);
    if ($uploadfile_size > 250000) {
      //アップロードファイルのサイズ上限をチェックします
      $errmsg .= "写真ファイルのサイズが大きすぎます。一枚0.25MB以下にしてください。<BR>";
    }
    elseif ($uploadfile_size == 0) {
      //アップロードファイルのサイズ下限をチェックします
      $errmsg .= "写真ファイルが存在しないか空のファイルです。<BR>";
    }
    elseif ($fileext != "JPG") {
      //アップロードファイルの拡張子をチェックします
      $errmsg .= "JPG形式以外の写真ファイルは登録できません。<BR>";
    }
    else {
      //アップロードファイルの内部登録名を組み立てます
      $mictime = microtime();
      $imagefile = substr($mictime, 11) . substr($mictime, 2, 6) . ".jpg";
    
     //テンポラリファイルを一時フォルダに名前を変えて移動します
      if (!move_uploaded_file($uploadfile, "$PHOTOTMP$imagefile"))  {
        $errmsg .= "写真ファイルのアップロードに失敗しました。<BR>";
      }
    }
  }
  //写真ファイルのアップロード処理
   if(strlen($uploadfile1) > 0 ) {
     //アップロードされたテンポラリファイルの情報を取得します
      $fileinfo1 = pathinfo($uploadfile_name1);
      $fileext1  = strtoupper($fileinfo1[extension]);
      if ($uploadfile_size1 > 250000) {
      //1アップロードファイルのサイズ上限をチェックします
      $errmsg .= "写真ファイルのサイズが大きすぎます。一枚0.25MB以下にしてください。<BR>";
    }
    elseif ($uploadfile_size1 == 0) {
      //1アップロードファイルのサイズ下限をチェックします
      $errmsg .= "写真ファイルが存在しないか空のファイルです。<BR>";
    }
    elseif ($fileext1 != "JPG") {
      //1アップロードファイルの拡張子をチェックします
      $errmsg .= "JPG形式以外の写真ファイルは登録できません。<BR>";
    }
    else {
      //1アップロードファイルの内部登録名を組み立てます
      $mictime = microtime();
      $imagefile1 = substr($mictime, 11) . substr($mictime, 2, 6) . ".jpg";
    
    //1テンポラリファイルを一時フォルダに名前を変えて移動します
      if (!move_uploaded_file($uploadfile1, "$PHOTOTMP$imagefile1"))  {
        $errmsg .= "写真ファイルのアップロードに失敗しました。<BR>";
    }
   }
  }
   if(strlen($uploadfile2) > 0 ) {
    //アップロードされたテンポラリファイルの情報を取得します
    $fileinfo2 = pathinfo($uploadfile_name2);
    $fileext2  = strtoupper($fileinfo2[extension]);
    if ($uploadfile_size2 > 250000) {
      //2アップロードファイルのサイズ上限をチェックします
      $errmsg .= "写真ファイルのサイズが大きすぎます。一枚0.25MB以下にしてください。<BR>";
    }
    elseif ($uploadfile_size2 == 0) {
      //2アップロードファイルのサイズ下限をチェックします
      $errmsg .= "写真ファイルが存在しないか空のファイルです。<BR>";
    }
    elseif ($fileext2 != "JPG") {
      //2アップロードファイルの拡張子をチェックします
      $errmsg .= "JPG形式以外の写真ファイルは登録できません。<BR>";
    }
    else {
      //2アップロードファイルの内部登録名を組み立てます
      $mictime = microtime();
      $imagefile2 = substr($mictime, 11) . substr($mictime, 2, 6) . ".jpg";
    

     //2テンポラリファイルを一時フォルダに名前を変えて移動します
      if (!move_uploaded_file($uploadfile2, "$PHOTOTMP$imagefile2"))  {
        $errmsg .= "写真ファイルのアップロードに失敗しました。<BR>";
      }
     }
    }
    if(strlen($uploadfile3) > 0 ) {
    //アップロードされたテンポラリファイルの情報を取得します
    $fileinfo3 = pathinfo($uploadfile_name3);
    $fileext3  = strtoupper($fileinfo3[extension]);
    if ($uploadfile_size3 > 250000) {
      //3アップロードファイルのサイズ上限をチェックします
      $errmsg .= "写真ファイルのサイズが大きすぎます。一枚0.25MB以下にしてください。<BR>";
    }
    elseif ($uploadfile_size3 == 0) {
      //3アップロードファイルのサイズ下限をチェックします
      $errmsg .= "写真ファイルが存在しないか空のファイルです。<BR>";
    }
    elseif ($fileext3 != "JPG") {
      //3アップロードファイルの拡張子をチェックします
      $errmsg .= "JPG形式以外の写真ファイルは登録できません。<BR>";
    }
    else {
      //3アップロードファイルの内部登録名を組み立てます
      $mictime = microtime();
      $imagefile3 = substr($mictime, 11) . substr($mictime, 2, 6) . ".jpg";
    
     //3テンポラリファイルを一時フォルダに名前を変えて移動します
      if (!move_uploaded_file($uploadfile3, "$PHOTOTMP$imagefile3"))  {
        $errmsg .= "写真ファイルのアップロードに失敗しました。<BR>";
      }
     }
    }

  if ($errmsg != "") {
    //いずれかの入力エラーがあったとき
    $body = $errmsg . "<BR><INPUT type='button' value='  戻る ' onClick='history.back()'>";
    //アップロードしたファイルの実体を削除します
    if(strlen($uploadfile) > 0 )   {
      @unlink($uploadfile);
      @unlink("$PHOTOTMP$imagefile");
     }
    if(strlen($uploadfile1) >0 )  {
      @unlink($uploadfile1);
      @unlink("$PHOTOTMP$imagefile1");
     }
    if(strlen($uploadfile2) >0)   {
      @unlink($uploadfile2);
      @unlink("$PHOTOTMP$imagefile2");
     }
    if(strlen($uploadfile3) >0)   {
      @unlink($uploadfile3);
      @unlink("$PHOTOTMP$imagefile3");
 }
 }
  
  else {
    //エラーがなければ確認用画面を組み立てます
    $body = "登録内容を確認してください。よければ[OK]ボタンをクリックしてください。
            <BR><BR>
            <FORM action='photomntexec.php' method='POST'>
            <TABLE class='formtable'>
         <TR>
                <TH>物件ID</TH><TD width='40' align='left'>$bukkenid</TD></TR>
         <TR>
                <TH>写真のファイル</TH>
                <TD>";
       
    if(strlen($uploadfile) > 0) {
      //写真がアップロードされたらそのファイルを表示します
      $body .= "<IMG src='$PHOTOTMP$imagefile'  width='120' height='90'>";
      }
    else {
      //アップロードされなかった場合は以前のファイルを使います
      $body .= "<IMG src='$PHOTODIR$oldfile'  width='120' height='90'>";
    }
    
       if(strlen($uploadfile1) > 0) {
      //1写真がアップロードされたらそのファイルを表示します
      $body .= "<IMG src='$PHOTOTMP$imagefile1'  width='120' height='90'>";
      }
     else {
      //1アップロードされなかったらそのファイルを表示します
      $body .= "<IMG src='$PHOTODIR$oldfile1'  width='120' height='90'>";
    }
    
    if(strlen($uploadfile2) > 0) {
       $body .= "<IMG src='$PHOTOTMP$imagefile2' width='120' height='90'>";
    }
      else {
       $body .= "<IMG src='$PHOTODIR$oldfile2' width='120' height='90'>";
    }
     if(strlen($uploadfile3) > 0) {
      //3,間取り図がアップロードされたらそのファイルを表示します
      $body .= "<IMG src='$PHOTOTMP$imagefile3'  width='120' height='90'>";
      }
     else {
      //3アップロードされなかったらそのファイルを表示します
      $body .= "<IMG src='$PHOTODIR$oldfile3'  width='120' height='90'>";
    }
    

    $body .= "<BR><BR>
              </TD>
              </TR>
               <TR>
                <TH>住所</TH><TD width='550'>$jyusyo<BR></TD></TR>
              <TR>
                <TH>最寄り駅</TH>
                  <TD width='550'>$moyorieki<BR>
                  </TD>
              </TR>
               <TR>
                <TH>カテゴリー</TH>
                <TD>";
                //カテゴリテーブルからカテゴリ名を検索します
              $body .= dfirst("categoryname", "tblcategory", "categoryid=$categoryid", True);
              $body .= "<BR>
                 </TD>
               </TR>
              <TR>
                <TH>カテゴリー1</TH>
                <TD>";
                //カテゴリテーブルからカテゴリ名を検索します
              $body .= dfirst("categoryname1", "tblcategory1", "categoryid=$categoryid1", True);
              $body .= "<BR>
                 </TD>
               </TR>  
        </TABLE>
    <TABLE class='formtable'>
     <TH>構造</TH><TD width='620'>$kouzou <BR></TD>
    </TABLE>
    <TABLE class='formtable'>
    <TR>
    
    <TH>敷地</TH><TD width='52'>$sikiti 坪<BR></TD>
    <TH>建物</TH><TD width='52'>$tatemono 坪<BR></TD>
    <TH>建蔽率</TH><TD width='52'>$kenpeiritu %<BR></TD>
    <TH>容積率</TH><TD width='52'>$yousekiritu %<BR></TD>
    <TH>間取り</TH><TD width='184'>$madori<BR></TD>
    </TR></TABLE>
    <TABLE class='formtable'>
    <TH>権利</TH><TD width='70'>$kenri <BR></TD>
    <TH>完成年</TH><TD width='80'>H. $kansei<BR></TD>
    <TH>価格</TH><TD width='90'>$sale 万円<BR></TD>
    <TH>現状</TH><TD width='90'>$genjyou<BR></TD>
    <TH>取引</TH> <TD width='90'>$torihiki<BR></TD>
    
    </TR></TABLE>
    <TABLE class='formtable'>
    <TH>用途</TH><TD width='150'>$youto<BR></TD>
    <TH>建築確認</TH><TD width='154'>$kakunin<BR></TD>
    <TH>接道</TH><TD width='192'>$miti<BR></TD>
    </TR></TABLE>
    <TABLE class='formtable'>
    <TH>駐車場</TH><TD width='50'>$kuruma<BR></TD>
    <TH>光彩</TH><TD width='80'>$kousai <BR></TD>
    <TH>引渡し</TH><TD width='53'>$hikiwatasi</TD>
    <TH>連絡先</TH><TD width='255'>$address<BR></TD>
    
    </TR></TABLE>
   
<TABLE class='formtable'>
              
              <TR>
                <TH>登録日</TH><TD width='550'>$regdate<BR></TD></TR>
              <TR>
                <TH>その他</TH><TD width='550'>$sonota<BR><BR></TD></TR>
              <TR>
                <TH>コメント</TH><TD width='550'>$comment<BR><BR></TD></TR>
              <TR>
                <TD colspan='2' align='center'>
                  <INPUT type='submit' name='regok' value='  ＯＫ  '>
                  <INPUT type='button' value='  戻る  ' onclick='history.back()'>
                  <INPUT type='submit' name='cancel' value='キャンセル'>
                  <INPUT type='hidden' name='imagefile'  value='$imagefile'>
                  <INPUT Type='hidden' name='imagefile1' value='$imagefile1'>
                  <INPUT Type='hidden' name='imagefile2' value='$imagefile2'>
                  <INPUT Type='hidden' name='imagefile3' value='$imagefile3'>
                  <INPUT type='hidden' name='categoryid' value='$categoryid'>
                  <INPUT type='hidden' name='categoryid1' value='$categoryid1'>
                  <INPUT type='hidden' name='jyusyo'     value='$jyusyo'>
                  <INPUT type='hidden' name='moyorieki'  value='$moyorieki'>
                  <INPUT type='hidden' name='sikiti'     value='$sikiti'>
                  <INPUT type='hidden' name='tatemono'   value='$tatemono'>
                  <INPUT type='hidden' name='madori'     value='$madori'>
                  <INPUT type='hidden' name='sale'       value='$sale'>
                  <INPUT type='hidden' name='kouzou'     value='$kouzou'>
                  <INPUT type='hidden' name='sonota'     value='$sonota'>
                  <INPUT type='hidden' name='comment'    value='$comment'>
                  <INPUT type='hidden' name='bukkenid'   value='$bukkenid'>
                  <INPUT type='hidden' name='kenri'     value='$kenri'>
                  <INPUT type='hidden' name='yousekiritu' value='$yousekiritu'>
                  <INPUT type='hidden' name='kenpeiritu'  value='$kenpeiritu'>
                  <INPUT type='hidden' name='miti'       value='$miti'>
                  <INPUT type='hidden' name='kousai'     value='$kousai'>
                  <INPUT type='hidden' name='torihiki'   value='$torihiki'>
                  <INPUT type='hidden' name='hikiwatasi' value='$hikiwatasi'>
                  <INPUT type='hidden' name='kouzou'     value='$kouzou'>
                  <INPUT type='hidden' name='genjyou'    value='$genjyou'>
                  <INPUT type='hidden' name='youto'    value='$youto'>
                  <INPUT type='hidden' name='address'    value='$address'>
                  <INPUT type='hidden' name='sale'       value='$sale'>
                  <INPUT type='hidden' name='kouzou'     value='$kouzou'>
                  <INPUT type='hidden' name='kakunin'    value='$kakunin'>
                  <INPUT type='hidden' name='kansei'     value='$kansei'>
                  <INPUT type='hidden' name='kuruma'     value='$kuruma'>

                  <INPUT type='hidden' name='bukkenid'   value='$bukkenid'>
                  <INPUT type='hidden' name='proc'       value='edit'>
                 </TD>
              </TR>
            </TABLE>
            </FORM>";
  }

  //ページヘッダを出力します
  print htmlheader("物件の編集確認");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();

?>
