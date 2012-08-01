<?php
/****************************************/
/* 検索結果ページ                       */
/****************************************/

  //共通データをインクルードします
  require_once("photolibini.php");

  //前のページからデータを受け取り
  $keyword  = $_GET[keyword];
  $regdate  = $_GET[regdate];
  $categid  = $_GET[categid];
  $categid1 = $_GET[categid1];
  $sorttype = $_GET[sorttype];
  $showtype = $_GET[showtype];
  $page     = $_GET[page];
  $tcnt     = $_GET[tcnt];

  //１ページ当りの表示件数を設定します
  $PAGESIZE = 9;
  //サムネイル表示時の１行当りの表示件数を設定します
  $ROWSIZE = 3;

  //検索の種類を判別します
  if (isset($keyword) and strlen($keyword) > 0) {
    //キーワード検索のとき
    $searchtype = "kw";
  }
  elseif (isset($regdate)) {
    //登録日別検索のとき
    $searchtype = "rd";
  }
  elseif (isset($categid)) {
    //カテゴリ別検索のとき
    $searchtype = "cg";
  }
  elseif (isset($categid1)) {
    //カテゴリ別検索1のとき
  $searchtype = "cg1";
  }
  else {
    //検索の種類が未指定またはキーワードが空のとき
    $body = "検索条件が指定されていません！
            <INPUT type='button' value='ホームへ戻る'
            onclick='window.location=\"index.htm\"'>";
    print htmlheader("検索結果") . $body . htmlfooter();
    exit();
  }

  //検索の種類に応じてWHERE条件を組み立てます
  switch ($searchtype) {
    case "kw":


      //キーワード検索のとき
      //キーワードからエスケープ文字を取り除きます
      $keyword = stripcslashes($keyword);
      //キーワードの前後のスペースを取り除きます
      $keyword = trim($keyword);
      //全角スペースの半角変換と半角カナの全角変換を行います
      $keyword = mb_convert_kana($keyword, "sKV", "EUC-JP");
      //キーワードをカンマかスペースで分解して配列に代入します
      if(!strrchr($keyword, " ")){
        //キーワードに半角スペースが含まれていないとき
        $keyword = str_replace("、", ",", $keyword);
        $keyword = str_replace("，", ",", $keyword);
        $arykey = explode(",", $keyword);
        $tmpkey = "Or";
        
      }
      else{
        //キーワードに半角スペースが含まれているとき
        $arykey = explode(" ", $keyword);
        $tmpkey = "And";
      }
      //分解された各キーワードが空でないかチェックします
      for ($i = 0; $i < sizeof($arykey); $i++) {
        if (strlen($arykey[$i]) == 0) {
          //分解されたキーワードのいずれかが空のとき
          $body = "キーワードの指定が正しくありません！
                  <INPUT type='button' value='ホームへ戻る'
                  onclick='window.location=\"index.htm\"'>";
          print htmlheader("検索結果") . $body . htmlfooter();
          exit();
        }
      }
      //最初のキーワードをWHERE句に追加します
        $where = " WHERE ((jyusyo Like \"%$arykey[0]%\") OR (comment Like \"%$arykey[0]%\") OR (sale Like \"%$arykey[0]%\") OR (sonota Like \"%$arykey[0]%\") OR (kouzou Like \"%$arykey[0]%\") OR (sikiti Like \"%$arykey[0]%\") OR (tatemono Like \"%$arykey[0]%\") OR (moyorieki Like \"%$arykey[0]%\") OR (madori Like \"%$arykey[0]%\"))";

      //２つめ以降のキーワードをWHERE句に追加します
      for ($i = 1; $i < sizeof($arykey); $i++) {
        $where .= " " . $tmpkey;
        $where .= " ((jyusyo Like \"%$arykey[$i]%\") OR (comment Like \"%$arykey[$i]%\") OR (sale Like \"%$arykey[$i]%\") OR (sonota Like \"%$arykey[$i]%\") OR (kouzou Like \"%$arykey[$i]%\") OR (sikiti Like \"%$arykey[$i]%\") OR (tatemono Like \"%$arykey[$i]%\") OR (moyorieki Like \"%$arykey[$i]%\") OR (madori Like \"%$arykey[$i]%\"))";
        
      }
      break;

    case "rd":
      //登録日別検索のとき
      $where = " WHERE regdate = \"$regdate\"";
      break;

    case "cg":
      //カテゴリ別検索のとき
      $where = " WHERE baibaibukken.categoryid = $categid";
      break;
    case "cg1":
      //カテゴリ別検索１のとき
      $where = " WHERE baibaibukken.categoryid1 = $categid1";
      break;

  }

  //並び順を設定します
  if (!isset($sorttype) or $sorttype == 1) {
    //はじめて呼ばれたときまたはカテゴリ順指定のとき
    $orderby = " ORDER BY baibaibukken.categoryid, bukkenid";
  }
  else {
    //登録日順指定のとき
    $orderby = " ORDER BY regdate, bukkenid";
  }

  //MySQLに接続します
  $con = mysql_connect($DBSERVER, $DBUSER, $DBPASSWORD);
  //MySQL読み込み時の文字コードを設定
  mysql_query("set names ujis");
  //データベースを選択します
  $selectdb = mysql_select_db($DBNAME, $con);

  if (!isset($page)) {
    //初めて呼ばれたときは総件数を取得します
    $sql = "SELECT Count(*) AS cnt FROM baibaibukken" . $where;

    $rst = mysql_query($sql, $con);

    $col = mysql_fetch_array($rst);
    $tcnt = $col[cnt];
    mysql_free_result($rst);
    //該当件数をチェックします
    if ($tcnt == 0) {
      $body = "該当する物件はみつかりませんでした！
              <INPUT type='button' value='ホームへ戻る'
              onclick='window.location=\"index.htm\"'>";
      print htmlheader("検索結果") . $body . htmlfooter();
      exit();
    }
    //現在ページを初期設定します
    $page = 1;
  }

  //総ページ数を計算します
  $totalpage = ceil($tcnt / $PAGESIZE);

  //ページ上部の表示を組み立てます
  $body = "$tcnt 件の物件がみつかりました。 ";
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
          RIGHT JOIN tblcategory ON baibaibukken.categoryid  = tblcategory.categoryid" . $where . $orderby .
          " LIMIT " . $PAGESIZE * ($page - 1) . ", $PAGESIZE";

  //結果セットを取得します
  $rst = mysql_query($sql, $con);

  //ページ本文を組み立てます
  $body .= "<BR><BR>";
  if (!isset($showtype) or $showtype == 1) {
    //一覧表示の場合
    
                
    //結果セットからデータをループで読み込みます
    while($col = mysql_fetch_array($rst)) {
      //各レコード内容を表示する表を組み立てます

     $body .="<TABLE class='photolist'>
            <TR><TH>物件ID</TH></TR>";

     $body .= "<TR> 
                <TD width='835' align='center'>" . nl2br($col[bukkenid]) . "</TD></TR></TABLE>";

      $body .= "<TABLE class='photolist'>
                <TR><TH>写真<SPAN class='smallfont'> (クリックで拡大)</SPAN></TH>
                <TH>写真<SPAN class='smallfont'> (クリックで拡大)</SPAN></TH>
                <TH>写真<SPAN class='smallfont'> (クリックで拡大)</SPAN></TH>
                <TH>間取り図<SPAN class='smallfont'>(クリックで拡大)</SPAN></TH>
                
                </TR>";


     
              
       $body .="<TD width='200' align='center'>
                  <A href='$PHOTODIR$col[photofilename]' target='_blank'>
                    <IMG src='$PHOTODIR$col[photofilename]' width='190' height='110'></A>
                </TD>
                
              <TD width='200' align='center'>
                  <A href='$PHOTODIR$col[photofilename1]' target='_blank'>
                    <IMG src='$PHOTODIR$col[photofilename1]'width='190' height='110'></A>
                </TD>
                
                  <TD width='200' align='center'>
                  <A href='$PHOTODIR$col[photofilename2]' target=' blank'>
                    <IMG src='$PHOTODIR$col[photofilename2]' width='190' height='110'></A>
                </TD>

                  <TD width='200' align='center'>
                  <A href='$PHOTODIR$col[madoriimagefile]' target=' blank'>
                    <IMG src='$PHOTODIR$col[madoriimagefile]' width='190' height='110'></A>
                </TR></TABLE>";



       $body .="<TABLE class='photolist'><TR>
                <TH>住所</TH>
                <TH>最寄り駅</TH>
             </TR>";
                

    $body .= "<TR><TD width='413'>" . nl2br($col[jyusyo]) . "</TD>
              <TD width='412'>" . nl2br($col[moyorieki]) . "</TD>
              </TR></TABLE>";

    $body .= "<TABLE class='photolist'>
               <TR>
                <TH>カテゴリー</TH>
                <TH>カテゴリー１</TH>
                <TH>構造</TH>
                <TH>敷地</TH>
                <TH>建物</TH>
                <TH>建蔽率</TH>
                <TH>容積率</TH>
                </TR>";

      $body .="<TR>
               <TD width='213'>$col[categoryname]</TD>
               <TD width='213'>$col[categoryname1]</TD>
               <TD width='180'>" . nl2br($col[kouzou]) . "</TD>
               <TD width='40'>" . nl2br($col[sikiti]) . "坪</TD>
               <TD width='40'>" . nl2br($col[tatemono]) . "坪</TD>
               <TD width='38' height='25'>" . nl2br($col[kenpeiritu]) . "%</TD>
               <TD width='38'>" . nl2br($col[yousekiritu]) . "%</TD>
               
               </RT></TABLE>";

     $body .= "<TABLE class='photolist'><TR>
                <TH>間取り</TH>
                <TH>権利</TH>
                <TH>完成年</TH>
                <TH>価格</TH>
                <TH>現状</TH>
                <TH>引渡し</TH>
                <TH>用途地域</TH>
                <TH>建築確認</TH>
                
                </TR>";
              

      $body .="<TR>
               <TD width='52'>" . nl2br($col[madori]) . "</TD>
               <TD width='52'>" . nl2br($col[kenri]) . "</TD>
               <TD width='87' align='center'>" . nl2br($col[kansei]) . "</TD>
               <TD width='105'>" . nl2br($col[sale]) . "万円</TD>
               <TD width='50'>" . nl2br($col[genjyou]) . "</TD>
               <TD width='67'>" . nl2br($col[hikiwatasi]) . "</TD>
               <TD width='190'>" . nl2br($col[youto]) . "</TD>
               <TD width='168'>" . nl2br($col[kakunin]) . "</TD>
               
               </TR></TABLE>";

      $body .= "<TABLE class='photolist'><TR>
               <TH>接道状況</TH>
                <TH>駐車場</TH>
                <TH>取引態様</TH>
                <TH>連絡先</TH>
                <TH>登録日</TH>
              </TR>";

      $body .= "<TR>
               <TD width='180'>" . nl2br($col[miti]) . "</TD>
               <TD width='60'>" . nl2br($col[kuruma]) . "</TD>
               <TD width='80'>" . nl2br($col[torihiki]) . "</TD>
               <TD width='388'>" . nl2br($col[renraku]) . "</TD>
               <TD width='90' align='center'>$col[regdate]</TD></TR></TABLE>";



      $body .= "<TABLE class='photolist'><TR>
               <TH>その他</TH>";

      $body .= "<TR>
               <TD width='832' height='25'>" . nl2br($col[sonota]) . "</TD>
               </TR></TR></TABLE>";



       $body .= "<TABLE class='photolist'><TR>
                <TH>コメント</TH></TR>";
                
       $body .= "<TR>
                <TD width='832' height='25'>" . nl2br($col[comment]) . "</TD></TR></TABLE>";



      $body .="<BR><BR><BR>";
                

      //キーワードが指定されているときはコメント内のキーワードを太字に置換します
      $tmpcomment = $col[comment];
      if ($searchtype == "kw") {
        for ($i = 0; $i < sizeof($arykey); $i++) {
          $tmpcomment = ereg_replace(preg_quote($arykey[$i]), "<B>". "\\0" . "</B>", $tmpcomment);
        }
      }
      //改行コードをBRタグに置換します
      $tmpcomment = nl2br($tmpcomment);
      
    }
    $body .= "</TABLE>";
  }
  else {
    //サムネイル表示の場合
    $body .= "<TABLE>";
    $colnum = 1;
    while($col = mysql_fetch_array($rst)) {
      if ($colnum == 1) {
        $body .= "<TR>";
      }
      //各レコード内容を表示する表を組み立てます
      $body .= "<TD width='130' align='center'>
                  <A href='$PHOTODIR$col[photofilename]' target='_blank'>
                    <IMG src='$PHOTODIR$col[photofilename]' width='120' height='90'></A>
                </TD>";
      if (++$colnum > $ROWSIZE) {
        //１行分表示したら次の行へ
        $body .= "</TR>";
        $colnum = 1;
      }
    }
    if ($colnum != 1) {
      $body .= "</TR>";
    }
    $body .= "</TABLE>";
  }

  //結果セットを破棄します
  mysql_free_result($rst);
  //MySQLとの接続を解除します
  $con = mysql_close($con);

  //ページナビゲーションのパラメータを設定します
  switch ($searchtype) {
    case "kw":
      $keynavi = "&keyword=" . urlencode($keyword);
      break;
    case "rd":
      $keynavi = "&regdate=$regdate";
      break;
    case "cg":
      $keynavi = "&categid=$categid";
      break;
    case "cg1":
      $keynavi = "&categid1=$categid1";
      break;
  }
  if (isset($sorttype)) {
    $keynavi .= "&sorttype=$sorttype";
  }
  if (isset($showtype)) {
    $keynavi .= "&showtype=$showtype";
  }

  //ページのナビゲーションを追加します
  $body .= "<DIV class='pagenavi'>";
  if ($page > 1) {
    //２ページ以降の場合は[前]を表示します
    $body .= "<A href = '$_SERVER[PHP_SELF]?page=" . ($page - 1) . "&tcnt=$tcnt$keynavi'>" .
              "&lt;前の $PAGESIZE 件</A>&nbsp;&nbsp;&nbsp;";
  }
  if ($totalpage > 1 and $page < $totalpage) {
    //全部で２ページ以上あってかつ現在が最終ページより
    //前のときは[次]を表示します
    $body .= "<A href = '$_SERVER[PHP_SELF]?page=" . ($page + 1) . "&tcnt=$tcnt$keynavi'>" .
              "次の $PAGESIZE 件&gt;</A>";
  }
  $body .= "</DIV>";

  //並べ替えと表示方法のフォームを組み立てます
  $body .= "<FORM action='$_SERVER[PHP_SELF]' method='GET'>
              <TABLE class='formtable'>
                <TR>
                  <TH>並べ替え</TH>
                  <TD>
                    <SELECT name='sorttype'>
                      <OPTION value='1'>カテゴリ順</OPTION>
                      <OPTION value='3'>カテゴリ順１</OPTON>
                      <OPTION value='2'>登録日順</OPTION>
                    </SELECT>
                  </TD>
                    <TD>
                    <INPUT type='submit' value='再表示'>
                  </TD>
                </TR>
              </TABLE>";
  switch ($searchtype) {
    case "kw":
      $body .= "<INPUT type='hidden' name='keyword' value='$keyword'>";
      break;
    case "rd":
      $body .= "<INPUT type='hidden' name='regdate' value='$regdate'>";
      break;
    case "cg":
      $body .= "<INPUT type='hidden' name='categid' value='$categid'>";
      break;
    case "cg1":
      $body .= "<INPUT type='hidden' name='categid1' value='$categid1'>";
      break;
  }
  $body .= "</FORM>";

  //ページヘッダを出力します
  print htmlheader("検索結果");
  //ページ本文を出力します
  print $body;
  //ページフッタを出力します
  print htmlfooter();

?>
