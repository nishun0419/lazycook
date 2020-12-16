<?php
//タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

//取得したAPIトークンを入れる
$apiKey = "1d67b536b2bf2c3d5040bf99e24ae46f";


if($_POST['route'] == 'backup'){

	$params = array(
		"body" => $_SERVER["REMOTE_ADDR"]."から".$_POST['project_name']."のバックアップ取得を開始します。"
	);

	// cURLでPOST
	$ch = curl_init();

	// cURLのオプション設定{roomId}の箇所には取得したルームIDを入れる
	curl_setopt($ch, CURLOPT_URL, "https://api.chatwork.com/v2/rooms/198547641/messages");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ChatWorkToken: '. $apiKey));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 結果を文字列で返す
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// サーバー証明書の検証を行わない
	curl_setopt($ch, CURLOPT_POST, true);				// HTTP POSTを実行
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));

	$rsp = curl_exec($ch);
	curl_close($ch);

	$database_name 		= $_POST['db_name'];	//DB名
	$database_host 		= $_POST['db_host'];	//ホスト名
	$acount_name 		= $_POST['db_user'];		//DBユーザー名
	$acount_password 	= $_POST['db_pass'];		//DBパスワード



	// 保存設定
	$file_Path = './';	// パス
	$file_Name = date('Ymd') .$_POST['project_name'] .'.sql';	// ファイル名(SQLファイル)
	$savePath = $file_Path . $file_Name;


	// mysqldumpの実行
	$command = 'mysqldump --single-transaction --default-character-set=binary ' . $database_name . ' --host=' . $database_host . ' --user=' . $acount_name . ' --password=' . $acount_password . ' > ' . $savePath;

	// ローカル環境の場合(XAMPP)
	// $command = 'C:\xampp\mysql\bin\mysqldump --single-transaction --default-character-set=binary ' . $database_name . ' --host=' . $database_host . ' --user=' . $acount_name . ' --password=' . $acount_password . ' > ' . $savePath;

	// exec処理の実行
	exec($command);



	// サイトのバックアップ
	$filename = date('Ymd').$_POST['project_name'].'.tgz';

	$exclusionList = $_POST['exexclusions'];

	//コマンドの生成
	$trans_zip = 'tar -cvzf '.$file_Path . $filename . ' '.'../'.' --exclude '.'../backup';

	if($exclusionList != null){

		$lists = str_replace(array("\r\n", "\r", "\n"), "\n", $exclusionList);
		$lists = explode("\n", $lists);

		foreach($lists as $list){
			$trans_zip .= ' --exclude ../'.$list;
		}
	}
	// ローカル環境の場合(XAMPP)
	// $command = 'C:\xampp\mysql\bin\mysqldump --single-transaction --default-character-set=binary ' . $database_name . ' --host=' . $database_host . ' --user=' . $acount_name . ' --password=' . $acount_password . ' > ' . $savePath;

	// exec処理の実行

	exec($trans_zip);

	// unlink($file_Name);
	// unlink($filename);

	//  // ダウンロード設定
	// header('Content-Type: application/octet-stream');
	// header('Content-Disposition: attachment; filename="' . $file_Name . '"');
	// header("Content-Transfer-Encoding: Binary");
	// header('Content-Length: ' . filesize($savePath));
	// ob_end_clean();
	// readfile($savePath);



	// PHPの終了
	print_r($trans_zip);
}
elseif($_POST['route'] == 'delete'){
	$params = array(
		"body" => $_SERVER["REMOTE_ADDR"]."から".$_POST['project_name']."のバックアップ取得を終了します。"
	);

	// cURLでPOST
	$ch = curl_init();

	// cURLのオプション設定{roomId}の箇所には取得したルームIDを入れる
	curl_setopt($ch, CURLOPT_URL, "https://api.chatwork.com/v2/rooms/198547641/messages");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ChatWorkToken: '. $apiKey));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 結果を文字列で返す
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// サーバー証明書の検証を行わない
	curl_setopt($ch, CURLOPT_POST, true);				// HTTP POSTを実行
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));

	$rsp = curl_exec($ch);
	curl_close($ch);

	unlink(date('Ymd').$_POST['project_name'].'.tgz');
	unlink(date('Ymd') .$_POST['project_name'] .'.sql');
	return true;
}
elseif($_POST['route'] == 'checkindex'){
	if($_POST['framework'] == 'WordPress'){
		$exclusionList = htmlspecialchars($_POST['exclusion']);
		$mode = htmlspecialchars($_POST['mode']);

		// ファイルを書き込みモードで開く
		$file_handle = fopen( "./exclusion.txt", "w");
		// ファイルへデータを書き込み
		fwrite( $file_handle, $mode."\n".$exclusionList);
		// ファイルを閉じる
		fclose($file_handle);
		$link = mysqli_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name']);

		// 接続状況をチェックします
		if (mysqli_connect_errno()) {
		    print_r(json_encode(array('失敗')));
		}

		$query = "SELECT option_value FROM wp_options WHERE option_id = 40 LIMIT 1";

		// クエリを実行します。
		if ($result = mysqli_query($link, $query)) {
			foreach ($result as $value) {
				print_r($value);
			}
		}

		exec('grep -n noindexSwitch\(\) ../wp-content/themes/'.$value['option_value'].'/functions.php | cut -d ":" -f 1',$opt);
		if(empty($opt)){
		$put_function = "function noindexSwitch() {
		//ここから環境ごとの設定//
		\$list = array();
		\$file_name = home_url().\"/backup/exclusion.txt\";
		//ファイルがあるのか確認する
		\$checkfile = get_headers(\$file_name);
		if(preg_match(\"/OK/\",\$checkfile[0]) ){
			\$exclusions = file(\$file_name);
			if(isset(\$exclusions)){
				for(\$i = 1; \$i < count(\$exclusions); \$i++){
					array_push(\$list,\$exclusions[\$i]);
				}
				\$envType = \$exclusions[0];//本番か開発か
			}
		}
		else{
			\$envType = \"prod\";
		}
		//ここまで環境ごとの設定//
		if (preg_match(\"/dev/\", \$envType)) {
			//noindexを入れる
			echo \"<meta name='robots' content='noindex,nofollow' />\n\";
		}else {
			foreach ((array)\$list as \$value) {
				\$RequestUri = \$_SERVER['REQUEST_URI'];
				\$trim_value = trim(\$value);
				if (\$_SERVER['REQUEST_URI'] == \"/\") {
					echo \"\";//TOPは無条件になにもしない
				}else {
					if(strstr(\$RequestUri, \$trim_value)) {
						//noindexを入れる
						echo \"<meta name='robots' content='noindex,nofollow' />\n\";
					}
				}
			}
		}
	}
	//デフォルトのnoindex処理を消してオリジナルの物で管理する
	remove_action( 'wp_head', 'noindex', 1 );
	add_action( 'wp_head', 'noindexSwitch');
	";
		$oldfile = '../wp-content/themes/'.$value['option_value'].'/functions.php';
		$newfile = file_get_contents('../wp-content/themes/'.$value['option_value'].'/functions.php');
		$newfile .= $put_function;
			file_put_contents($oldfile, $newfile);
		}
		//ベタnoindexを削除
		exec("grep -l -E '<meta name=\"robots\" content=\".*noindex.*\".*>' ../wp-content/themes/".$value['option_value']."/* --exclude='*.bak*' | xargs sed -i.bak".date('Y-m-d')." -e 's/<meta name=\"robots\" content=\".*noindex.*\".*>/ /g'",$output);
		print_r($output);
	}
	elseif($_POST['framework'] == 'EC-CUBE'){
		$exclusionList = htmlspecialchars($_POST['exclusion']);
		$mode = htmlspecialchars($_POST['mode']);
		$mode = trim($mode);
		print_r($mode);

		if(file_exists('../app/template/default/default_frame.twig')){
			exec('grep -n Noindex自動化処理 ../app/template/default/default_frame.twig | cut -d ":" -f 1',$opt);
			if(empty($opt)){
				//新規に挿入する処理
				exec('grep -n content=\".*meta_robots.*\" ../app/template/default/default_frame.twig | cut -d ":" -f 1',$line);
				$linecount = (int)$line[0];
				//除外リストを配列に格納する
				$list = str_replace(array("\r\n", "\r", "\n"), "\n", $exclusionList);
				$exclusions = explode("\n", $list);
				if($exclusions[0] != ''){
					//追加するif文
					$if_method = "{% if ";
					$loopcount = 0;
					foreach($exclusions as $exclusion){
						if($loopcount == 0){
							//何もしない
						}
						else{
							$if_method .= "or ";
						}
						$if_method .= "'".$exclusion."' in app.request.uri ";
						$loopcount++;
					}
					$if_method .="%}";
				}
				$putcontent = $linecount."a {# Noindex自動化処理 #}{% else %}";
				if($exclusions[0] != '' && $mode != "dev"){
					$putcontent .= $if_method.'<meta name=\"robots\" content=\"noindex,nofollow\"> {% endif %}';
				}
				else if($mode == 'dev'){
					$putcontent .= '<meta name=\"robots\" content=\"noindex,nofollow\">';

				}
				else{
					//何もしない
				}
				exec('sed -e "'.$putcontent.'" ../app/template/default/default_frame.twig',$opt2);
				print_r($opt2);
				$rewritefile = fopen("../app/template/default/default_frame.twig", "w");
				foreach($opt2 as $value){
   					fwrite($rewritefile, $value."\n");
				}
				fclose($rewritefile);
			}
			else{
				//上書き処理
				$linecount = $opt[0];
				exec('sed '.$linecount.'d '.'../app/template/default/default_frame.twig',$opt0);
				print_r($opt0);
				$rewritefile = fopen("../app/template/default/default_frame.twig", "w");
				foreach($opt0 as $value){
   					fwrite($rewritefile, $value."\n");
				}
				fclose($rewritefile);
				exec('grep -n content=\".*meta_robots.*\" ../app/template/default/default_frame.twig | cut -d ":" -f 1',$line);
				$linecount = (int)$line[0];
				//除外リストを配列に格納する
				$list = str_replace(array("\r\n", "\r", "\n"), "\n", $exclusionList);
				$exclusions = explode("\n", $list);
				print_r($exclusions);
				if($exclusions[0] != ''){
					//追加するif文
					$if_method = "{% if ";
					$loopcount = 0;
					foreach($exclusions as $exclusion){
						if($loopcount == 0){
							//何もしない
						}
						else{
							$if_method .= "or ";
						}
						$if_method .= "'".$exclusion."' in app.request.uri ";
						$loopcount++;
					}
					$if_method .="%}";
				}
				$putcontent = $linecount."a {# Noindex自動化処理 #}{% else %}";
				if($exclusions[0] != '' && $mode != "dev"){
					$putcontent .= $if_method.'<meta name=\"robots\" content=\"noindex,nofollow\"> {% endif %}';
				}
				else if($mode == 'dev'){
					$putcontent .= '<meta name=\"robots\" content=\"noindex,nofollow\">';

				}
				else{
					//何もしない
				}
				exec('sed -e "'.$putcontent.'" ../app/template/default/default_frame.twig',$opt2);
				print_r($opt2);
				$rewritefile = fopen("../app/template/default/default_frame.twig", "w");
				foreach($opt2 as $value){
   					fwrite($rewritefile, $value."\n");
				}
				fclose($rewritefile);
			}
		}
		else{
			//srcにファイルがある場合
			exec('grep -n Noindex自動化処理 ../src/Eccube/Resource/template/default/default_frame.twig | cut -d ":" -f 1',$opt);
			if(empty($opt)){
				//新規に挿入する処理
				exec('grep -n content=\".*meta_robots.*\" ../src/Eccube/Resource/template/default/default_frame.twig | cut -d ":" -f 1',$line);
				$linecount = (int)$line[0];
				//除外リストを配列に格納する
				$list = str_replace(array("\r\n", "\r", "\n"), "\n", $exclusionList);
				$exclusions = explode("\n", $list);
				print_r($exclusions);
				if($exclusions[0] != ''){
					//追加するif文
					$if_method = "{% if ";
					$loopcount = 0;
					foreach($exclusions as $exclusion){
						if($loopcount == 0){
							//何もしない
						}
						else{
							$if_method .= "or ";
						}
						$if_method .= "'".$exclusion."' in app.request.uri ";
						$loopcount++;
					}
					$if_method .="%}";
				}
				$putcontent = $linecount."a {# Noindex自動化処理 #}{% else %}";
				if($exclusions[0] != '' && $mode != "dev"){
					$putcontent .= $if_method.'<meta name=\"robots\" content=\"noindex,nofollow\"> {% endif %}';
				}
				else if($mode == 'dev'){
					$putcontent .= '<meta name=\"robots\" content=\"noindex,nofollow\">';

				}
				else{
					//何もしない
				}
				exec('sed -e "'.$putcontent.'" ../src/Eccube/Resource/template/default/default_frame.twig',$opt2);
				print_r($opt2);
				$rewritefile = fopen("../src/Eccube/Resource/template/default/default_frame.twig", "w");
				foreach($opt2 as $value){
   					fwrite($rewritefile, $value."\n");
				}
				fclose($rewritefile);
			}
			else{
				//上書き処理
				$linecount = $opt[0];
				exec('sed '.$linecount.'d '.'../src/Eccube/Resource/template/default/default_frame.twig',$opt0);
				print_r($opt0);
				$rewritefile = fopen("../src/Eccube/Resource/template/default/default_frame.twig", "w");
				foreach($opt0 as $value){
   					fwrite($rewritefile, $value."\n");
				}
				fclose($rewritefile);
				exec('grep -n content=\".*meta_robots.*\" ../src/Eccube/Resource/template/default/default_frame.twig | cut -d ":" -f 1',$line);
				$linecount = (int)$line[0];
				//除外リストを配列に格納する
				$list = str_replace(array("\r\n", "\r", "\n"), "\n", $exclusionList);
				$exclusions = explode("\n", $list);
				print_r($exclusions);
				if($exclusions[0] != ''){
					//追加するif文
					$if_method = "{% if ";
					$loopcount = 0;
					foreach($exclusions as $exclusion){
						if($loopcount == 0){
							//何もしない
						}
						else{
							$if_method .= "or ";
						}
						$if_method .= "'".$exclusion."' in app.request.uri ";
						$loopcount++;
					}
					$if_method .="%}";
				}
				$putcontent = $linecount."a {# Noindex自動化処理 #}{% else %}";
				if($exclusions[0] != '' && $mode != "dev"){
					$putcontent .= $if_method.'<meta name=\"robots\" content=\"noindex,nofollow\"> {% endif %}';
				}
				else if($mode == 'dev'){
					$putcontent .= '<meta name=\"robots\" content=\"noindex,nofollow\">';

				}
				else{
					//何もしない
				}
				print_r('sed -e '.$putcontent.' ../src/Eccube/Resource/template/default/default_frame.twig');
				exec('sed -e "'.$putcontent.'" ../src/Eccube/Resource/template/default/default_frame.twig',$opt2);
				print_r($opt2);
				$rewritefile = fopen("../src/Eccube/Resource/template/default/default_frame.twig", "w");
				foreach($opt2 as $value){
   					fwrite($rewritefile, $value."\n");
				}
				fclose($rewritefile);
			}
		}
		return true;
	}
	elseif($_POST['framework'] == 'Laravel'){
		exec('chmod 777 noindex.sh');
		$output = shell_exec("sh ./noindex.sh");

		echo json_encode(array($output));
	}
}
else{
	http_response_code( 404 ) ;
	exit;
}
?>