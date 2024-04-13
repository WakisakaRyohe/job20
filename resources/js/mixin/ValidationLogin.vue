<script>
export default {
	// リアルタイムバリデーション
	watch: {
		'form.email': {
			handler: function () {
				this.errors.email = [];

				// 正規表現パターンを定義
				const regex = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/;

				if(this.form.email){
					// console.log('メールアドレスがある場合');

					// メールアドレスの形式が違う場合
					if(!regex.test(this.form.email)) {
						this.errors.email.push('メールアドレスの形式で入力してください。');
						// console.log('メールアドレスの形式が違う場合');

					// 50文字以上の場合
					}else if(this.form.email.length > 50){
						this.errors.email.push('50文字以下で入力してください。');
						// console.log('50文字以上の場合');
					}

				// メールアドレスが空の場合
				}else{
					this.errors.email.push('メールアドレスは入力必須です。');
					// console.log('メールアドレスが空の場合');
				}

				// vuexにデータ登録
				this.$store.commit('Login/setEmail', this.form.email);
			},
		},

		'form.password': {
			handler: function () {
				this.errors.password = [];

				// 正規表現パターンを定義
				const regex = /^[a-zA-Z0-9]+$/;

				if(this.form.password){
					// console.log('パスワードがある場合');

					if(!regex.test(this.form.password)) {
						this.errors.password.push('パスワードは、半角英数字で入力してください。');
						// console.log('パスワードが半角英数字以外の場合');

					}else if(this.form.password.length > 0 && this.form.password.length < 8) {
						this.errors.password.push('パスワードは、8文字以上で入力してください。');
						// console.log('パスワードが8文字以下の場合');

					}else if(this.form.password.length > 32) {
						this.errors.password.push('パスワードは、32文字以内で入力してください。');
						// console.log('パスワードが32文字以上の場合');
					}
					
				// パスワードが空の場合
				}else{
					this.errors.password.push('パスワードは入力必須です。');
					// console.log('パスワードが空の場合');
				}
			},
		},

		// フォームデータ(全項目のエラーメッセージが削除された後に判定)
		form: {
			handler: function () {
				if(this.form.email && this.form.password && 
				   ! this.getErrorMessage(this.errors.email) && 
				   ! this.getErrorMessage(this.errors.password)){
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
