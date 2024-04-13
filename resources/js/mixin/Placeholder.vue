<script>
export default {
	data(){
		return {
			placeholderPassword: '',
			placeholderPasswordConfirmation: '',
		}
	},

	methods:{
		// 画面幅を検知するイベント
		handleResize: function() {
			this.width = window.innerWidth;

			// パスワードのプレースホルダー変更
			if(this.width < 414){
				this.placeholderPassword = '半角英数8-32文字で入力';
				this.placeholderPasswordConfirmation = 'パスワードを再入力';
			}else{
				this.placeholderPassword = '半角英数8-32文字で入力してください';
				this.placeholderPasswordConfirmation = 'パスワードを再入力してください';
			}
		},
	},

	mounted(){
		this.handleResize();
		// 画面幅を取得するイベントを設定
		window.addEventListener('resize', this.handleResize);
	},

	beforeDestroy() {
		//EventListener削除
		window.removeEventListener('resize', this.handleResize)
	},
}
</script>
