<script>
export default {
	data(){
		return {
			isInput: false,
		}
	},

	// リアルタイムバリデーション
	watch: {
		// お名前
		name: {
			handler: function () {
				this.errors.name = [];

				// 正規表現パターンを定義(ひらがな・カタカナ・漢字を含む)
				const regex = /^[ぁ-んァ-ヶー一-龯]+$/;

				// お名前がある場合
				if(this.name){
					// console.log('お名前がある場合');

					// お名前が、ひらがな・カタカナ・漢字でない場合
					if(!regex.test(this.name)) {
						this.errors.name.push('お名前は、ひらがな・カタカナ・漢字で入力してください');
						// console.log('お名前が、ひらがな・カタカナ・漢字でない場合');

					// お名前が50文字以上の場合
					}else if(this.name.length > 50){
						this.errors.name.push('お名前は、50文字以下で入力してください');
						// console.log('お名前が50文字以上の場合');
					}

				// 送信後の場合
				}else if(this.name === null){
					// console.log('お名前 送信後の場合');

				// お名前がない場合
				}else{
					this.errors.name.push('お名前は入力必須です');
					// console.log('お名前がない場合');
				}
			},
			immediate: true,
		},

		// メールアドレス
		email: {
			handler: function () {
				this.errors.email = [];

				// 正規表現パターンを定義
				const regex = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/;

				// メールアドレスがある場合
				if(this.email){
					// console.log('メールアドレスがある場合');

					// メールアドレスの形式が違う場合
					if(!regex.test(this.email)) {
						this.errors.email.push('メールアドレスの形式で入力してください');
						// console.log('メールアドレスの形式が違う場合');

					// メールアドレスが50文字以上の場合
					}else if(this.email.length > 50){
						this.errors.email.push('メールアドレスは、50文字以下で入力してください');
						// console.log('メールアドレスが50文字以上の場合');
					}

				// 送信後の場合
				}else if(this.email === null){
					// console.log('メールアドレス 送信後の場合');

				// メールアドレスがない場合
				}else{
					this.errors.email.push('メールアドレスは入力必須です');
					// console.log('メールアドレスがない場合');
				}
			},
			immediate: true,
		},

		// お問い合わせの種類
		type: {
			handler: function () {
				this.errors.type = [];

				// お問い合わせの種類が未選択の場合
				if(this.type == 0 && this.isSubmit === false){
					this.errors.type.push('お問い合わせの種類は選択必須です');
					// console.log('お問い合わせの種類が未選択の場合');

				// 送信後の場合
				}else if(this.type == 0 && this.isSubmit === true){
					// console.log('お問い合わせの種類 送信後の場合');

					// 送信済み判定フラグ変更
					this.$store.commit('Contact/setIsSubmit', false);
					// console.log('IsSubmit: ' + this.isSubmit);
				}
			},
			immediate: true,
		},

		// 件名
		subject: {
			handler: function () {
				this.errors.subject = [];

				// 件名が50文字以上の場合
				if(this.subject && this.subject.length > 50){
					this.errors.subject.push('件名は、50文字以下で入力してください');
					// console.log('件名が50文字以上の場合');

				// 送信後の場合
				}else if(this.subject === null){
					// console.log('件名 送信後の場合');

				// 件名がない場合
				}else if(!this.subject){
					this.errors.subject.push('件名は入力必須です');
					// console.log('件名がない場合');
				}
			},
			immediate: true,
		},

		// お問い合わせ内容
		message: {
			handler: function () {
				// テキストエリアの高さ変更
				this.resizeMessageArea();
				// エラー配列を空にする
				this.errors.message = [];
				// 正規表現パターンを定義(改行とスペース以外の文字を含む)
				const regex = /.*[ぁ-んァ-ヶー一-龯0-9０-９a-zA-Z!?_+*'"`#$%&\-^\\@;:,./=~|[\](){}<>¥！-／：-＠［-｀｛-～、-〜”’・￥].*/;

				// お問い合わせ内容がある場合
				if(this.message){
					this.isInput = true;
					// console.log('a1:お問い合わせ内容がある場合');
					
					// お問い合わせ内容が改行とスペース以外の文字を含まない場合
					if( !regex.test(this.message) ){
						// 入力値は空判定
						this.isInput = false;
						// console.log('a2:お問い合わせ内容が改行とスペース以外の文字を含まない場合');
						// console.log('入力値: ' + this.isInput);

					// お問い合わせ内容が500文字以上の場合
					}else if(this.message.length > 500){
						// console.log('a3:お問い合わせ内容が500文字以上の場合');
						this.errors.message.push('お問い合わせ内容は、500文字以下で入力してください。');
					}

				// 送信後の場合
				}else if(this.message === null){
					// console.log('お問い合わせ内容 送信後の場合');

				// お問い合わせ内容がない場合
				}else{
					this.isInput = false;
					this.errors.message.push('お問い合わせ内容は入力必須です');
					// console.log('a4:お問い合わせ内容がない場合');
				}
			},
			immediate: true,
		},

		// フォームデータ(全項目のエラーメッセージが削除された後に判定)
		form: {
			handler: function () {
				if(	this.name && this.email && this.type && this.subject && this.message && this.isInput && 
					!this.getErrorMessage(this.errors.name) && !this.getErrorMessage(this.errors.email) && 
					!this.getErrorMessage(this.errors.type) && !this.getErrorMessage(this.errors.subject) && 
					!this.getErrorMessage(this.errors.message) ){
					this.isDisabled = false;
				}else{
					this.isDisabled = true;
				}
			},
			deep: true,
			immediate: true,
		},
	},
}
</script>
