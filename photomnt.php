<?php
/****************************************/
/* 物件のメンテナンスページ             */
/****************************************/

  //共通データをインクルードします
  require_once ("photolibini.php");

  //セッションを開始します
  session_start();

  //ログインチェック
  if (!session_is_registered($ADMSESS)) {
    //所定のセッション変数が定義されていない（＝未ログイン）のとき
    //ログインページへジャンプします
    header("location: adminlogin.php?id=2");
    exit();
  }

  //前のページからデータを受け取り
  $page = $_GET[page];
  $tcnt = $_GET[tcnt];

  //１ページ当りの表示件数を設定します
  $PAGESIZE = 10;

  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);

  if (!isset($page)) {
    //初めて呼ばれたときは総件数を取得します
    $sql = "SELECT Count(*) AS cnt FROM baibaibukken";
    $rst = mysql_query($sql, $con);
    $col = mysql_fetch_array($rst);
    $tcnt = $col[cnt];
    mysql_free_result($rst);
    //該当件数をチェックします
    if ($tcnt == 0) {
      $body = "登録されている物件はまだありません！
              <INPUT type='button' value='ホームへ戻る'
              onclick='window.location=\"tourokukannri.htm\"'>";
      print htmlheader("物件のメンテナンス") . $body . htmlfooter();
      exit();
    }
    //現在ページを初期設定します
    $page = 1;
  }

  //総ページ数を計算します
  $totalpage = ceil($tcnt / $PAGESIZE);

  //JavaScriptを記述します
  $body .= "<SCRIPT language='JavaScript'><!--
            function EditExec(bukkenid) {
              document.mainfrm.action = 'photoupd.php';
              document.mainfrm.bukkenid.value = bukkenid;
              document.mainfrm.submit();
            }
            function DeleteCheck(bukkenid) {
              if(confirm('本当に削除していいですか？')){
                document.mainfrm.action = 'photomntexec.php';
                document.mainfrm.bukkenid.value = bukkenid;
                document.mainfrm.proc.value = 'del';
                document.mainfrm.submit();
              }
            }
            // --></SCRIPT>";

  //ページ上部の表示を組み立てます
  $body .= "$tcnt 件の写真が登録されています。 ";
  $body .= "[" . ($PAGESIZE * ($page - 1) + 1) . "-";
  if ($page < $totalpage) {
    //最終ページより前のページのとき
    $body .= ($PAGESIZE * $page) . "] を表示";
  }
  else {
    //最終ページのとき
    $body .= "$tcnt] を表示";
  }

  //１ページ分だけ抽出するSQL文を組み立てます
  $sql = "SELECT baibaibukken.*, categoryname
          FROM baibaibukken
          RIGHT JOIN tblcategory ON baibaibukken.categoryid  = tblcategory.categoryid
          ORDER BY bukkenid DESC
          LIMIT " . $PAGESIZE * ($page - 1) . ", $PAGESIZE";

  //結果セットを取得します
  $rst = mysql_query($sql, $con);
   while($col = mysql_fetch_array($rst)) {
  //ページ本文を組み立てます
  //結果セットからデータをループで読み込みます
  //各レコード内容を表示する表を組み立てます
  $body .= "<BR><BR>
            <FORM name='mainfrm' method='POST'>";
  
  $body .="<TABLE class='photolist'>
            <TR><TH>物件ID</TH></TR>";

     $body .= "<TR> 
                <TD width='947' height='25' align='center'>" . nl2br($col[bukkenid]) . "</TD></TR></TABLE>";

  $body .= "<TABLE class='photolist'>
                <TR><TH>写真<SPAN class='smallfont'> (クリックで拡大)</SPAN></TH>
                <TH>写真<SPAN class='smallfont'> (クリックで拡大)</SPAN></TH>
                <TH>写真<SPAN class='smallfont'> (クリックで拡大)</SPAN></TH>
                <TH>間取り図<SPAN class='smallfont'>(クリックで拡大)</SPAN></TH>
                </TR>";


    $body .="<TD width='230' align='center'>
                  <A href='$PHOTODIR$col[photofilename]' target='_blank'>
                    <IMG src='$PHOTODIR$col[photofilename]' width='200' height='120'></A>
                </TD>
                
              <TD width='230' align='center'>
                  <A href='$PHOTODIR$col[photofilename1]' target='_blank'>
                    <IMG src='$PHOTODIR$col[photofilename1]'width='200' height='120'></A>
                </TD>
                
                  <TD width='230' align='center'>
                  <A href='$PHOTODIR$col[photofilename2]' target=' blank'>
                    <IMG src='$PHOTODIR$col[photofilename2]' width='200' height='120'></A>
                </TD>

                  <TD width='230' align='center'>
                  <A href='$PHOTODIR$col[madoriimagefile]' target=' blank'>
                    <IMG src='$PHOTODIR$col[madoriimagefile]' width='200' height='120'></A>

                </TR></TABLE>";

    $body .="<TABLE class='photolist'><TR>
                <TH>住所</TH>
                <TH>最寄り駅</TH>";



    $body .= "<TR><TD width='488' height='25'>" . nl2br($col[jyusyo]) . "</TD>
              <TD width='450'>" . nl2br($col[moyorieki]) . "</TD></TABLE>";

    $body .= " <TABLE class='photolist'>
                <TR>
                <TH>カテゴリー</TH>
                <TH>カテゴリー1</TH>
                <TH>構造</TH>
                </TR>";

    $body .= "<TD width='325' height='25'>$col[categoryname]</TD>
              <TD width='325' height='25'>$col[categoryname1]</TD>
              <TD width='280' height='25'>" . nl2br($col[kouzou]) . "</TD>
              
              </TABLE>";


    $body .= "<TABLE class='photolist'>
              <TR>
                <TH>敷地</TH>
                <TH>建物</TH>
                <TH>建蔽率</TH>
                <TH>容積率</TH>
                <TH>間取り</TH>
                <TH>権利</TH>
                <TH>完成年</TH>
                <TH>価格</TH>
                <TH>現状</TH>
                <TH>取引</TH>
                </TR>";

     $body .="<TR>
               <TD width='70' height='25' align='right'>" . nl2br($col[sikiti]) . "坪</TD>
               <TD width='70' height='25' align='right'>" . nl2br($col[tatemono]) . "坪</TD>
               <TD width='70' height='25'align='right'>" . nl2br($col[kenpeiritu]) . "%</TD>
               <TD width='70' height='25' align='right'>" . nl2br($col[yousekiritu]) . "%</TD>
               <TD width='207'>" . nl2br($col[madori]) . "</TD>
               <TD width='60'>" . nl2br($col[kenri]) . "</TD>
               <TD width='100'>H." . nl2br($col[kansei]) . "</TD>
               <TD width='100' align='right'>" . nl2br($col[sale]) . "万円</TD>
               <TD width='70'>" . nl2br($col[genjyou]) . "</TD>
               <TD width='50'>" . nl2br($col[torihiki]) . "</TD>
               </RT></TABLE>";

     $body .= "<TABLE class='photolist'><TR>
                <TH>用途地域</TH>
                <TH>建築確認</TH>
                <TH>接道状況</TH>
                <TH>駐車場</TH>
                <TH>光彩</TH>
                <TH>引渡し</TH>
                </TR>";

     $body .="<TR>
               <TD width='220' height='25'>" . nl2br($col[youto]) . "</TD>
               <TD width='220' height='25'>" . nl2br($col[kakunin]) . "</TD>
               <TD width='233' height='25'>" . nl2br($col[miti]) . "</TD>
               <TD width='60' height='25'>" . nl2br($col[kuruma]) . "</TD>
               <TD width='100' height='25'>" . nl2br($col[kousai]) . "</TD>
               <TD width='70'>" . nl2br($col[hikiwatasi]) . "</TD>
              </TR></TABLE>";

     $body .="<TABLE class='photolist'><TR>
                <TH>連絡先</TH>
                <TH>登録日</TH>
              </TR>";

     $body .="<TR>
               <TD width='838' height='25'>" . nl2br($col[address]) . "</TD>
               <TD width='100' height='25' align='center'>$col[regdate]</TD>
               </TR></TABLE>";

     $body .= "<TABLE class='photolist'><TR>
                <TH>その他</TH></TR>";

     $body .= "<TR>
               <TD width='945' height='25'>" . nl2br($col[sonota]) . "</TD>
               </TR></TABLE>";

     $body .= "<TABLE class='photolist'><TR>

               <TR><TH>コメント</TH></TR>";

     $body .= "<TR>
                <TD width='945' height='25'>" . nl2br($col[comment]) . "</TD></TR></TABLE>
                <TABLE class='photolist'><TR>
                <TD width='945' height='25' align='center'>
                <INPUT type='button' value='編集' onclick='EditExec(\"$col[bukkenid]\");'>
                <INPUT type='button' value='削除' onclick='DeleteCheck(\"$col[bukkenid]\");'>
                </TD></TR></TABLE>";

     $body .="<BR><BR><BR>";

  }
  $body .= "</TABLE>
            <INPUT type='hidden' name='bukkenid'>
            <INPUT type='hidden' name='proc'>
            </FORM>";

  //結果セットを破棄します
  mysql_free_result($rst);
  //MySQLとの接続を解除します
  $con = mysql_close($con);

  //ページのナビゲーションを追加します
  $body .= "<DIV class='pagenavi'>";
  if ($page > 1) {
    //２ページ以降の場合は[先頭]と[前]を表示します
    $body .= "<A href = '$_SERVER[PHP_SELF]?page=1&tcnt=$tcnt'>&lt;&lt;先頭へ</A>&nbsp;&nbsp;&nbsp;";
    $body .= "<A href = '$_SERVER[PHP_SELF]?page=" . ($page - 1) . "&tcnt=$tcnt'>" .
              "&lt;前の $PAGESIZE 件</A>&nbsp;&nbsp;&nbsp;";
  }
  if ($totalpage > 1 and $page < $totalpage) {
    //全部で２ページ以上あってかつ現在が最終ページより
    //前のときは[次]と[最後]を表示します
    $body .= "<A href = '$_SERVER[PHP_SELF]?page=" . ($page + 1) . "&tcnt=$tcnt'>" .
              "次の $PAGESIZE 件&gt;</A>&nbsp;&nbsp;&nbsp;";
    $body .= "<A href = '$_SERVER[PHP_SELF]?page=$totalpage&tcnt=$tcnt'>最後へ&gt;&gt;</A>";
  }
  $body .= "</DIV>";


  //ページヘッダを出力します
  print htmlheader("物件のメンテナンス");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();

?>
