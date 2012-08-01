<?php
/****************************************/
/* 物件の編集ページ                     */
/****************************************/
  //共通データをインクルードします
  require_once("photolibini.php");

  //前のページからデータを受け取り
  $bukkenid = $_POST[bukkenid];

  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);
 print var_dump($DBNAME, $com);

  //指定の写真データのみを読み込むSQLを組み立てます
  $sql = "SELECT * FROM baibaibukken WHERE bukkenid = $bukkenid";
  //結果セットを取得、データを変数に代入します
  $rst = mysql_query($sql, $con);

  $col = mysql_fetch_array($rst);


  $bukkenid       = $col[bukkenid];
  $photofilename  = $col[photofilename];
  $photofilename1 = $col[photofilename1];
  $photofilename2 = $col[photofilename2];
  $photofilename3 = $col[madoriimagefile];
  $categoryid     = $col[categoryid];
  $categoryid1    = $col[categoryid1];
  $jyusyo         = $col[jyusyo];
  $moyorieki      = $col[moyorieki];
  $sikiti         = $col[sikiti];
  $tatemono       = $col[tatemono];
  $madori         = $col[madori];
  $sale           = $col[sale];
  $kouzou         = $col[kouzou];
  $sonota         = $col[sonota];
  $comment        = $col[comment];
  $regdate        = $col[regdate];
  $torihiki       = $col[torihiki];
  $hikiwatasi     = $col[hikiwatasi];
  $genjyou        = $col[genjyou];
  $kenri          = $col[kenri];
  $youto          = $col[youto];
  $kuruma         = $col[kuruma];
  $kousai         = $col[kousai];
  $kansei         = $col[kansei];
  $kakunin        = $col[kakunin];
  $address        = $col[address];
  $kenpeiritu     = $col[kenpeiritu];
  $yousekiritu    = $col[yousekiritu];
  $miti           = $col[miti];



  //結果セットを破棄します
  mysql_free_result($rst);

  //すべてのカテゴリを読み込むSQLを組み立てます
  $sql = "SELECT * FROM tblcategory";
  //結果セットを取得します
  $rst = mysql_query($sql, $con);
  
  //カテゴリのオプションメニューを組み立てます
    $stroption = "";
      while ($col = mysql_fetch_array($rst)) {
    $stroption .= "<OPTION value='$col[categoryid]'" .
                  ($col[categoryid] == $categoryid ? " selected" : "") .
                  ">$col[categoryname]</OPTION>";
  }
  //結果セットを破棄します
  mysql_free_result($rst);

  //MySQLとの接続を解除します
  $con = mysql_close($con);

  //ページヘッダを出力します
  print htmlheader("物件の編集");

?>

    変更内容を入力して、[登録]ボタンをクリックしてください。<BR>
<BR>
<FORM action="photoupdchk.php" method="POST" enctype="multipart/form-data">
<DIV align="left">*印の付いた項目は入力必須です。</DIV> <BR>
<TABLE class="formtable">
  <TR>
  <TH>物件ID</TH>
    <TD>
      <?php echo $bukkenid?>
    </TD>
  </TR>
  <TR>
    <TH>現在の写真</TH>
    <TD>
      <IMG src="<?="$PHOTODIR$photofilename"?>" width="120" height="90">
     <IMG src="<?="$PHOTODIR$photofilename1"?>" width="120" height="90">
      <IMG src="<?="$PHOTODIR$photofilename2"?>" width="120" height="90">
     <IMG src="<?="$PHOTODIR$photofilename3"?>" width="120" height="90">
    </TD>
  </TR>

  <TR>
    <TH>写真のファイル<BR>
    <SPAN class="midfont">JPG形式のみ、<BR>一枚0.25MB以下</SPAN></TH>
    <TD>
      <INPUT type="file" name="uploadfile" size="60"><BR>
      <SPAN class="midfont">1,指定したときのみ写真が変更されます。</SPAN><BR>
      <INPUT type="file" name="uploadfile1" size="60"><BR>
      <SPAN class="midfont">2,指定したときのみ写真が変更されます。</SPAN><BR>
      <INPUT type="file" name="uploadfile2" size="60"><BR>
      <SPAN class="midfont">3,指定したときのみ写真が変更されます。</SPAN><BR>
      <INPUT type="file" name="uploadfile3" size="60"><BR>
      <SPAN class="midfont">4,指定したときのみ写真が変更されます。</SPAN><BR>

  </TD>
  </TR>
  
  <TR>
    <TH>住所 <BR>*60</TH>
    <TD>
       <INPUT size='60' type="text" name="jyusyo"><BR>
       <?php echo $jyusyo?>
       
    </TD>
  <TR>
  <TH>最寄り駅<BR>
    <SPAN class="midfont">40文字まで</SPAN></TH>
    <TD>
       <TEXTAREA rows="3" clos="70" name="moyorieki" ><?php echo $moyorieki?></TEXTAREA>
    </TD>
    </TR>
   <TR>
    <TH>カテゴリ *</TH>
    <TD>
    <SELECT name="categoryid">
        <OPTION value="0" selected>--カテゴリを選択してください--</OPTION>
        <?php echo $stroption?>
    </SELECT>
    </TD>
   </TR>
   <TR>
    <TH>カテゴリ1 *</TH>
    <TD>
    <SELECT name="categoryid1">
        <OPTION value="0" selected>--カテゴリを選択してください--</OPTION>
        <?php echo $stroption1?>
    </SELECT>
    </TD>
   </TR>
   <TR>
    <TH>構造<BR>*12</TH>
    <TD>
       <INPUT size="20" type="text" name="kouzou"><BR><?php echo $kouzou?>
    </TD>
   </TR>

</TABLE>
  <TABLE class="formtable">
   <TR>
      <TH>敷地<BR>*4</TH>
    <TD>
       <INPUT size='4' type="text" name="sikiti">坪<BR><?php echo $sikiti?>
    </TD>
      <TH>建物<BR>*4</TH>
    <TD>
       <INPUT size='4' type="text" name="tatemono">坪<BR><?php echo $tatemono?>
    </TD>
      <TH>建蔽率<BR>*3</TH>
    <TD>
       <INPUT size='4' type="text" name="kenpeiritu">%<BR><?php echo $kenpeiritu?>
    </TD>
      <TH>容積率<BR>*3</TH>
    <TD>
       <INPUT size='4' type="text" name="yousekiritu">%<BR><?php echo $yousekiritu?>
    </TD></TR> 
    <TR>
      <TH>間取り<BR>*10</TH>
     <TD>
        <INPUT size="15" type="text" name="madori"><BR><?php echo $madori?>
     </TD>
       <TH>権利<BR>*9</TH>
    <TD>
       <INPUT size="5" type="text" name="kenri"><BR><?php echo $kenri?>
    </TD>
       <TH>完成年<BR>*12</TH>
    <TD>
       H.<INPUT size="9" type="text" name="kansei"><BR>H.<?php echo $kansei?>
    </TD>
       <TH>価格<BR>*9</TH>
    <TD>
       <INPUT size="5" type="text" name="sale">万円<BR><?php echo $sale?>
    </TD></TR>
    <TR>
      <TH>現状<BR>*4</TH>
    <TD>
       <INPUT size="5" type="text" name="genjyou"><BR><?php echo $genjyou?>
    </TD>
      <TH>取引<BR>*5</TH>
    <TD>
       <INPUT size="5" type="text" name="torihiki"><BR><?php echo $torihiki?>
    </TD>
       <TH>用途<BR>*9</TH>
    <TD>
       <INPUT size='15' type="text" name="youto"><BR><?php echo $youto?>
    </TD>
       <TH>建築確認<BR>*10</TH>
    <TD>
       <INPUT size="9" type="text" name="kakunin"><BR><?php echo $kakunin?>
    </TD></TR>
    <TR>
      <TH>接道状況<BR>*9</TH>
    <TD>
       <INPUT size='15' type="text" name="miti"><BR><?php echo $miti?>
    </TD>
      <TH>駐車場<BR>*6</TH>
    <TD>
       <INPUT size="4" type="text" name="kuruma"><BR><?php echo $kuruma?>
    </TD>
      <TH>光彩<BR>*9</TH>
    <TD>
       <INPUT size="7" type="text" name="kousai"><BR><?php echo $kousai?>
    </TD>    
    <TH>引渡し<BR>*4</TH>
    <TD>
       <INPUT size="5" type="text" name="hikiwatasi"><BR><?php echo $hikiwatasi?>
    </TD></TR>
    
    
    
  </TABLE>
  <TABLE class="formtable">
  
    <TH>連絡先<BR>*20</TH>
    <TD>
       <INPUT size="84" type="text" name="address"><BR><?php echo $address?>
    </TD>
</TABLE>
  <TABLE class="formtable">
  <TR>
     <TH>その他<BR>
     <SPAN class="midfont">50文字まで</SPAN></TH>
     <TD>
       <TEXTAREA rows="1" cols="72" name="sonota"><?php echo $sonota?></TEXTAREA>
     </TD>
  </TR>
  <TR>
     <TH>コメント<BR>
     <SPAN class="midfont">255文字まで</SPAN></TH>
    <TD>
      <TEXTAREA rows="7" cols="60" name="comment"><?php echo $comment?></TEXTAREA>
    </TD>
  </TR>
  <TR>
    <TH>登録日</TH><TD><?=$regdate?></TD></TR>
  <TR>
    <TD colspan="2" align="center">
      <INPUT type="submit" name="reg" value="  登録  ">
      <INPUT type="button" value="  戻る  " onclick="history.back()">
      <INPUT type="hidden" name="bukkenid"  value="<?php echo $bukkenid?>">
      <INPUT type="hidden" name="oldfile"  value="<?php echo $photofilename?>">
      <INPUT type="hidden" name="oldfile1" value="<?php echo $photofilename1?>">
      <INPUT type="hidden" name="oldfile2" value="<?php echo $photofilename2?>">
      <INPUT type="hidden" name="oldfile3" value="<?php echo $photofilename3?>">
      <INPUT type="hidden" name="regdate"  value="<?php echo $regdate?>">
     
      
    </TD>
  </TR>
</TABLE>
</FORM>
<?php
  //ページフッタを出力します
  print htmlfooter();

?>
