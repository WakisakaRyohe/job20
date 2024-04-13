<script>
export default {
	// リアルタイムバリデーション
	watch: {
		errors: {
			handler: function () {
				if(this.email && this.password && this.password_confirmation && 
				   ! this.getErrorMessage(this.errors.email) && 
				   ! this.getErrorMessage(this.errors.password) && 
				   ! this.getErrorMessage(this.errors.password_confirmation) ){
					this.isDisabled = false;
				}else{
					this.isDisabled = true;
				}
			},
			deep: true,
		},

		email: {
			handler: function () {
				this.errors.email = [];

				// 正規表現パターンを定義
				const regex = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]{1,}\.[A-Za-z0-9]{1,}$/;

				if(this.email){
					// メールアドレスの形式が違う場合
					if(!regex.test(this.email)) {
						this.errors.email.push('メールアドレスの形式で入力してください。');
						// console.log('メールアドレスの形式が違う場合');

					// 50文字以上の場合
					}else if(this.email.length > 50){
						this.errors.email.push('50文字以下で入力してください。');
						// console.log('50文字以上の場合');
					}

				// メールアドレスが空の場合
				}else{
					this.errors.email.push('メールアドレスは入力必須です。');
					// console.log('メールアドレスが空の場合');
				}

				// vuexにメールアドレス記録
				this.$store.commit('Register/setEmail', this.email);

				// VueXにエラーメッセージ記録
				const errorMessage = this.errors.email[0];

				if(errorMessage){
					this.$store.commit('Register/reset_errors_email', []);
					this.$store.commit('Register/set_errors_email', errorMessage);

					if(!this.email){
						this.$store.commit('Register/reset_errors_email', []);
					}
				}
			}
		},

		password: function () {
			this.errors.password = [];

			// 正規表現パターンを定義
			const regex = /^[a-zA-Z0-9]+$/;

			if(this.password){
				// console.log('パスワードがある場合');

				if(!regex.test(this.password)) {
					this.errors.password.push('パスワードは、半角英数字で入力してください。');
				}else if(this.password.length > 0 && this.password.length < 8) {
					this.errors.password.push('パスワードは、8文字以上で入力してください。');
				}else if(this.password.length > 32) {
					this.errors.password.push('パスワードは、32文字以内で入力してください。');
				}
				
				// パスワード(確認)がない、またはパスワードとパスワード(確認)が違う場合
				if(!this.password_confirmation || this.password !== this.password_confirmation) {
					this.errors.password_confirmation = [];
					this.errors.password_confirmation.push('パスワードと同じ値を入力してください。');
					// console.log('パスワード(確認)がない、またはパスワードとパスワード(確認)が違う場合');
				}

			// パスワードが空の場合
			}else{
				this.errors.password.push('パスワードは入力必須です。');
				// console.log('パスワードが空の場合');

				// パスワード(確認)が空の場合
				if(!this.password_confirmation) {
					this.errors.password_confirmation = [];
					// console.log('パスワード(確認)が空の場合');
				}
			}
		},

		password_confirmation: function () {
			this.errors.password_confirmation = [];

			if(this.password_confirmation) {
				// console.log('パスワード(確認)がある場合');

				// パスワード(確認)がパスワードと違う場合
				if(this.password !== this.password_confirmation) {
					this.errors.password_confirmation.push('パスワードと同じ値を入力してください。');
					// console.log('パスワード(確認)がパスワードと違う場合');
				}
			}else{
				this.errors.password_confirmation.push('パスワード(確認)は入力必須です。');
				// console.log('パスワード(確認)が空の場合');
			}
		},
	},
}
</script>
