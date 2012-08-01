<?php
/****************************************/
/* 物件の登録ページ                 */
/****************************************/

  //共通データをインクルードします
  require_once("photolibini.php");

  //セッションを開始します
  session_start();

  //ログインチェック
  if (!session_is_registered($ADMSESS)) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    header("location: adminlogin.php?id=1");
    exit();
  }

  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);
  
  //すべてのカテゴリを読み込むSQLを組み立てます
  $sql = "SELECT * FROM tblcategory";

  //結果セットを取得します
  $rst = mysql_query($sql, $con);

  //カテゴリのオプションメニューを組み立てます
  $stroption = "";
  while ($col = mysql_fetch_array($rst)) {
    $stroption .= "<OPTION value='$col[categoryid]'>$col[categoryname]</OPTION>";
  }

  //結果セットを破棄します
  mysql_free_result($rst);
  //MySQLとの接続を解除します
  $con = mysql_close($con);

  //ページヘッダを出力します
  print htmlheader("物件の新規登録");

?>

新しく登録する物件の情報を入力して、[登録]ボタンをクリックしてください。<BR>
<BR>
<FORM action="photonewchk.php" method="POST" enctype="multipart/form-data">
<DIV align="left">*最高入力文字数です。</DIV><BR>
<TABLE class="formtable">
  <TR>
    <TH>写真のファイル *<BR>
    <SPAN class="midfont">JPG形式のみ、0.25MB以下</SPAN></TH>
    <TD>
      <INPUT type="file" name="uploadfile" size="60">
    </TD>
  </TR>
   <TR>
     <TH>写真のファイル1 *<BR>
     <SPAN class="midfont">JPG形式のみ、0.25MB以下</SPAN></TH>
     <TD>
        <INPUT type="file" name="uploadfile1" size="60">
     </TD>
   </TR>
   <TR>
     <TH>写真のファイル２ *<BR>
     <SPAN class="midfont">JPG形式のみ、0.25MB以下</SPAN></TH>
     <TD>
        <INPUT type="file" name="uploadfile2" size="60">
     </TD>
    </TR>

    <TR>
     <TH>間取り図 *<BR>
     <SPAN class="midfont">JPG形式のみ、0.1MB以下</SPAN></TH>
     <TD>
        <INPUT type="file" name="uploadfile3" size="60">
     </TD>
    </TR>

    <TR>
     <TH>カテゴリー *</TH>
    <TD>
      <SELECT name="categoryid">
        <OPTION value="0" selected>--カテゴリを選択してください--</OPTION>
        <?=$stroption?>
      </SELECT>
    </TD>
  </TR>
  <TR>
    <TH>住所 *60</TH>
    <TD>
      <INPUT size="91" type="text" name="jyusyo">
    </TD>
  </TR>
  <TR>
     <TH>最寄り駅<BR>
     <SPAN class="midfont">40文字まで</SPAN></TH>
     <TD>
        <TEXTAREA rows="2" cols="59" name="moyorieki"></TEXTAREA>
     </TD>
   </TR></TABLE>
  <TABLE class=" formtable">
  
   <TR>
     <TH>取引*5</TH>
     <TD>
     <INPUT size="5" type="text" name="torihiki">
     </TD>
     <TH>引渡し*4</TH>
     <TD>
     <INPUT size="5" type="text" name="hikiwatasi">
     </TD>
     <TH>敷地*4</TH>
    <TD>
      <INPUT size="3" type="text" name="sikiti">坪
    </TD>
     <TH>建物*4</TH>
    <TD>
      <INPUT size="3" type="text" name="tatetubo">坪
    </TD>
     <TH>建蔽率*3</TH>
    <TD>
      <INPUT size="2" type="text" name="kenpeiritu">%
    </TD>
     <TH>容積率*3</TH>
    <TD>
      <INPUT size="2" type="text" name="yousekiritu">%
    </TD>
    </TR></TABLE>
    <TABLE class=" formtable">
     <TR>
      <TH>構造*12</TH>
       <TD>
       <INPUT size="30"  type="text" name="kouzou">
       </TD>
   <TH>用途地域*9</TH>
      <TD>
      <INPUT size="30" type="text" name="youto">
      </TD>
     <TH>現状*4</TH>
     <TD>
      <INPUT size="6" type="text" name="genjyou">
     </TD>
     </TR>
     <TH>接道状況*9</TH>
     <TD>
     <INPUT size="25" type="text" name="miti">
     </TD>
     <TH>光彩*9</TH>
     <TD>
     <INPUT size="10" type="text" name="kousai">
     </TD>
    </TR></TABLE>
  <TABLE class="formtable">
     <TR>
      <TH>間取り*10</TH>
      <TD>
      <INPUT size="12" type="text" name="madori">
      </TD>
    <TH>駐車場*6</TH>
    <TD>
     <INPUT size="4" type="text" name="kuruma">
    </TD>
    
  <TH>価格*9</TH>
    <TD>
     <INPUT size="5" type="text" name="sale">万円
    </TD>
   <TH>権利*9</TH>
    <TD>
     <INPUT size="5" type="text" name="kenri">
    </TD>
    <TH>完成年*12</TH>
     <TD>
      H.<INPUT size="10" type="text" name="kansei">
     </TD>
  </TR></TABLE>
  <TR>
<TABLE class="formtable">
   <TR>
     <TH>建築確認*10</TH>
       <TD>
       <INPUT size="27" type="text" name="kakunin">
       </TD>
     <TH>連絡先*20</TH>
       <TD>
       <INPUT size="59" type="text" name="address">
       </TD>
   </TR></TABLE>
<TABLE class="formtable">
  <TR>
    <TH>その他*50<BR>
    <SPAN class="midfont">50文字まで</SPAN></TH>
    <TD>
      <INPUT size="90" type="text" name="sonota">
    </TD>
  </TR>
  <TR>
     <TH>物件コメント<BR>
     <SPAN class="midfont">255文字まで</SPAN></TH>
     <TD>
        <TEXTAREA rows="3" cols="86" name="comment"></TEXTAREA>
     </TD>
   </TR>
  <TR>
    <TD colspan="2" align="center">
      <INPUT type="submit" name="reg" value="  登録  ">
      <INPUT type="reset" value="  クリア  ">
    </TD>
  </TR>
</TABLE>
</FORM>

<?php
  //ページフッタを出力します
  print htmlfooter();

?>
