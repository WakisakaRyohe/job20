<script>
export default {
	data(){
		return {
			// 画面の横幅
			width: 0,
			// 画面サイズ判定
			isWidthS: false,
			isWidthM: false,
			isWidthL: false,
			isWidthXL: false,
		}
	},

	methods:{
		// 画面幅を取得
		handleResize: function() {
			this.width = window.innerWidth;

			if(this.width <= 414){
				this.isWidthS = true;
				this.isWidthM = false;
				this.isWidthL = false;
				this.isWidthXL = false;
			}else if(this.width <= 768 && this.width > 414){
				this.isWidthS = false;
				this.isWidthM = true;
				this.isWidthL = false;
				this.isWidthXL = false;
			}else if(this.width <= 1040 && this.width > 768){
				this.isWidthS = false;
				this.isWidthM = false;
				this.isWidthL = true;
				this.isWidthXL = false;
			}else{
				this.isWidthS = false;
				this.isWidthM = false;
				this.isWidthL = false;
				this.isWidthXL = true;
			}
		},
	},

	mounted(){
		// 画面幅を取得
		this.handleResize();
		// イベント設定
		window.addEventListener('resize', this.handleResize);
	},

	beforeDestroy() {
		//EventListener削除
		window.removeEventListener('resize', this.handleResize);
	},
}
</script>
