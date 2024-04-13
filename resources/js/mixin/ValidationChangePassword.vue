<script>
export default {
	// リアルタイムバリデーション
	watch: {
		errors: {
			handler: function () {
				if(this.password_old && this.password_new && this.password_new_confirmation && 
					! this.getErrorMessage(this.errors.password_old) && 
					! this.getErrorMessage(this.errors.password_new) && 
					! this.getErrorMessage(this.errors.password_new_confirmation) ){
					this.isDisabled = false;
				}else{
					this.isDisabled = true;
				}
			},
			deep: true,
		},

		// 現在のパスワード
		password_old: function(){
			this.errors.password_old = [];

			if(this.password_old){
				// console.log('a1:現在のパスワードあり');

				// axiosで使うthisを格納
				const self = this;

				axios.post('/job20/web/change_password_check', {
					password_old: self.password_old,
					password_db: self.password_db,
				}).then(response => {
					if(response.data.successFlg) {
						if(response.data.result) {
							self.errors.password_old = [];
						}else{
							self.errors.password_old.push('現在のパスワードが違います。');
						}
					// エラーの場合
					}else{
						self.$router.push({ name: 'error'})
					}
				})
				.catch(erorr => console.log(error));
				
			}else{
				this.errors.password_old = [];
				this.errors.password_old.push('現在のパスワードは入力必須です。');
				// console.log('a2:現在のパスワードなし');

				// 全パスワードない場合
				if(!this.password_new && !this.password_new_confirmation) {
					this.errors.password_old = [];
					this.errors.password_new = [];
					this.errors.password_new_confirmation = [];
					// console.log('a3:全パスワードない場合');
				}
			}
		},

		// 新しいパスワード
		password_new: function(){
			this.errors.password_new = [];

			// 正規表現パターンを定義
			const regex = /^[a-zA-Z0-9]+$/;

			if(this.password_new){
				// console.log('b1:新しいパスワードあり');

				if(!regex.test(this.password_new)) {
					this.errors.password_new.push('新しいパスワードは、半角英数字で入力してください。');
				
				}else if(this.password_new.length > 0 && this.password_new.length < 8) {
					this.errors.password_new.push('新しいパスワードは、8文字以上で入力してください。');
				
				}else if(this.password_new.length > 32) {
					this.errors.password_new.push('新しいパスワードは、32文字以内で入力してください。');
				
				}else if(this.password_new === this.password_old) {
					this.errors.password_new.push('新しいパスワードと現在のパスワードは、違う値を入力してください。');
				}
				
				// 「新しいパスワード(確認)」のエラーメッセージ削除
				this.errors.password_new_confirmation = [];

				// 「新しいパスワード(確認)」がある場合
				if(this.password_new_confirmation) {
					// console.log('b2:「新しいパスワード(確認)」がある場合');

					// 「新しいパスワード」と同じ場合
					if(this.password_new === this.password_new_confirmation) {
						// console.log('b3:「新しいパスワード」と同じ場合');

					// 「新しいパスワード」と違う場合
					}else{
						this.errors.password_new_confirmation.push('「新しいパスワード」と同じパスワードを入力してください。');
						// console.log('b4:「新しいパスワード」と違う場合');
					}
					
				// 「新しいパスワード(確認)」がない場合
				}else{
					// console.log('b5:「新しいパスワード(確認)」がない場合');
				}

			// 「新しいパスワード(確認)」がない場合
			}else{
				// console.log('b5:新しいパスワードがない場合');
				this.errors.password_new.push('新しいパスワードは入力必須です。');

				// 全パスワードない場合
				if(!this.password_old && !this.password_new_confirmation) {
					this.errors.password_old = [];
					this.errors.password_new = [];
					this.errors.password_new_confirmation = [];
					// console.log('b6:全パスワードない場合');
				}
			}
		},

		// 新しいパスワード(確認)
		password_new_confirmation: function(){
			this.errors.password_new_confirmation = [];

			// 「新しいパスワード(確認)」がある場合
			if(this.password_new_confirmation) {
				// console.log('c1:「新しいパスワード(確認)」がある場合');

				// 「新しいパスワード」と違うパスワードの場合
				if(this.password_new !== this.password_new_confirmation && this.errors.password_new.length === 0) {
					this.errors.password_new_confirmation.push('「新しいパスワード」と同じパスワードを入力してください。');
					// console.log('c2:「新しいパスワード」と違うパスワードの場合');
				}

			// 「新しいパスワード」がない場合
			}else{
				// console.log('c3:「新しいパスワード(確認)」がない場合');
				this.errors.password_new_confirmation.push('新しいパスワード(確認)は入力必須です。');

				// 全パスワードない場合
				if(!this.password_old && !this.password_new) {
					this.errors.password_old = [];
					this.errors.password_new = [];
					this.errors.password_new_confirmation = [];
					// console.log('c4:全パスワードない場合');
				}
			}
		},
	},
}
</script>
