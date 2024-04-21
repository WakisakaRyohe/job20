<template>
	<div class="c-carousel">
		<div class="c-carousel__title" v-if="!topFlg">
			<span class="c-badge--carousel ">{{ today }}更新</span>
			<span class="c-carousel__titleText">注目の求人をピックアップ</span>
		</div>
		<div ref="slideArea" class="c-carousel__slideArea">
			<div class="c-carousel__wrapper">
				<ul class="c-carousel__list" :style="{ left: slideLeft, width: getListWidth }" :class="{ resetSlide: isResetSlide }" 
					@touchstart="touchstart($event)" @touchend="touchend($event)">
					<li class="c-carousel__item" :class="{top: topFlg}" 
						:style="{ width: getItemWidth }" v-for="(job, index) in jobArray" :key="index">
						<router-link class="c-carousel__link c-jobData" :to="`/job20/job/${job.id}`">
							<!-- 写真エリア -->
							<img class="c-jobData__photo--carousel" :src = "'https://d38rk2cibjrg07.cloudfront.net/job_change_20/' + job.photo1">
							<div class="c-jobData__body">
								<!-- 職種バッジ -->
								<div class="c-jobData__badgeWrap">
									<div class="c-jobData__badgeItem--occupation c-jobData__badgeItem--carousel">{{ job.occupation_name }}</div>
								</div>
								<!-- 求人名 -->
								<div class="c-jobData__jobName c-lineClamp--line2to3">{{ job.job_name }}</div>
								<!-- 会社情報エリア -->
								<div class="c-jobData__companyArea">
									<span class="c-jobData__companyImg">
										<img :src="'https://d38rk2cibjrg07.cloudfront.net/job_change_20/' + job.company_img">
									</span>
									<span class="c-jobData__companyName c-lineClamp--line1 u-mb0">{{ job.company_name }}</span>
								</div>
							</div>
						</router-link>
					</li>
				</ul>
				<!-- 左にずらすボタン -->
				<button type="button" class="c-carousel__btn--left" :class="{ disableClick: isDisableClick }" @click="prev">
					<i class="fas fa-angle-left"></i>
				</button>
				<!-- 右にずらすボタン -->
				<button type="button" class="c-carousel__btn--right" :class="{ disableClick: isDisableClick }" @click="next">
					<i class="fas fa-angle-right"></i>
				</button>
			</div>
		</div>

		<!-- 下部の丸い点 -->
		<div class="c-carousel__pager">
			<div class="c-carousel__page" v-for="n in listNum" :key="n" :class="[ {disableClick: isDisableClick}, {active: n === jobNum} ]" @click="move(n)">●</div>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'jobList',
		'topFlg',
	],

	data() {
		return {
			jobArray: [],
			jobNum: 1,
			listNum: 5,
			slideAreaWidth: 0,
			listWidth: 0,
			itemHeight: 0,
			itemWidth: 0,
			isResetSlide: false,
			isDisableClick: false,
			timerId: undefined,
			width: 0,
			// タッチイベント
			touchStart: 0,
			touchEnd: 0,
		};
	},

	methods: {
		// 画面幅を検知
		handleResize: function() {
			this.getSize();
		},

		// カルーセル要素の幅を検知
		getSize() {
			this.width = window.innerWidth;
			this.slideAreaWidth = this.$refs.slideArea.clientWidth;
			
			if(this.width < 414){
				this.itemWidth = this.slideAreaWidth;
				this.listWidth = this.itemWidth * 9;
			}else{
				this.listWidth = this.slideAreaWidth * 54 / 10;
				this.itemWidth = this.listWidth / 9;
			}

			this.itemHeight = this.itemWidth * 6 / 10;
		},

		//右に動く処理
		next(){
			this.isDisableClick = true;

			if(this.jobNum === this.listNum){
				this.jobNum++;

				// スライド終了後に一瞬で移動
				setTimeout(()=>{
					this.isResetSlide = true;
					this.jobNum = 1;
				}, 350);

			}else{
				this.jobNum++;
			}

			setTimeout(()=>{
				this.isResetSlide = false;
				this.isDisableClick = false;
			}, 400);

			// this.resetInterval();
		},

		//左に動く処理
		prev(){
			this.isDisableClick = true;

			if(this.jobNum === 1){
				this.jobNum--;

				// スライド終了後に一瞬で移動
				setTimeout(()=>{
					this.isResetSlide = true;
					this.jobNum = this.listNum;
				}, 350);

			}else{
				this.jobNum--;
			}

			setTimeout(()=>{
				this.isResetSlide = false;
				this.isDisableClick = false;
			}, 400);

			// this.resetInterval();
		},

		// ページャーのクリック
		move(num){
			// ボタン連打無効化
			this.isDisableClick = true;
			this.jobNum = num;

			setTimeout(()=>{
				this.isDisableClick = false;
			}, 400);

			// this.resetInterval();
		},

		resetInterval(){
			// ５秒ごとにスライドするイベント削除して再開
			clearInterval(this.timerId);
			this.timerId = setInterval(this.next, 5000);
		},

		//タッチイベント
		touchstart(e){ //タップしたとき
			this.touchStart = e.changedTouches[0].pageX; // 位置を取得
		},
		touchend(e){ //指を離したとき
			this.touchEnd = e.changedTouches[0].pageX; // 位置を取得

			//最初と最後の指の位置の差を取得
			const touchDiff = this.touchStart - this.touchEnd;
			// console.log(touchDiff);

			 //上の値が正　=　左にフリック
			if(touchDiff > 50){
				this.next(); //右に一つ画像を変更

			//上の値が負　=　右にフリック
			} else if(touchDiff < -50) {
				this.prev(); //左に一つ画像を変更
			}
		},
	},

	computed: {
		isLoading: function () {
			return this.$store.state.Pagination.isLoading;
		},

		// 現在のpositionからleftに変換
		slideLeft() {
			if(this.width < 414){
				return "-" + String( (this.itemWidth * (this.jobNum - 1) ) + (this.itemWidth * 2) ) + "px";
			}else{
				return "-" + String( (this.itemWidth * (this.jobNum - 1) ) + this.slideAreaWidth) + "px";
			}
		},

		getListWidth() {
			return String(this.listWidth) + "px";
		},

		getItemWidth() {
			return String(this.itemWidth) + "px";
		},

		today() {
			const today = new Date();
			const month = String( today.getMonth() + 1 );
			const date = String(today.getDate() );
			// 曜日
			// const weekday = ["日","月","火","水","木","金","土"];
			// const day = weekday[ today.getDay() ]; // getDay()は0~6の数値
			// 返却する文字列
			// const todayText = month + "月" + date + '日' + '('  + day + ')';
			const todayText = month + "月" + date + '日';
			return todayText;
		},
	},

	created(){
		// 親から渡された配列をコピー
		let copyArray = this.jobList.concat();

		// 配列の先頭２つと末尾２つの要素を、配列形式で取得
		let head2Jobs = copyArray.slice(0, 2);
		let last2Jobs = copyArray.slice(-2);

		// ３つの配列を結合
		this.jobArray = last2Jobs.concat(copyArray, head2Jobs);

		// ５秒ごとにスライド
		// this.timerId = setInterval(this.next, 5000);
		
		// 画面幅を取得するイベントを設定
		window.addEventListener('resize', this.handleResize);
	},

	mounted(){
		// カルーセルのサイズを取得(refのサイズを取得するため、createdでは使えない)
		this.getSize();
	},

	updated(){
	},

	beforeDestroy() {
		// ５秒ごとにスライドするイベント削除
		clearInterval(this.timerId);
		//EventListener削除
		window.removeEventListener('resize', this.handleResize)
	},

}
</script>

<style>
.resetSlide{
	transition: left 0s ease;
}
.disableClick{
	pointer-events: none;
}
</style>
