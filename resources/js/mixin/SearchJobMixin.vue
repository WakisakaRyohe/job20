<script>
import SearchModalMixin from '../mixin/SearchModalMixin.vue';
import SearchPanelData from '../mixin/SearchPanelData.vue';

export default {
	mixins: [ SearchModalMixin, SearchPanelData ],

	data(){
		return {
		}
	},

	methods:{
		// タブレットより小さい画面で検索パネルの表示切り替え
		reverseActiveSidebar(){
			if (this.$store.state.Pagination.isActiveSearchPanel === true) {
				this.$store.commit('Pagination/setIsActiveSearchPanel', false);
			}else{
				this.$store.commit('Pagination/setIsActiveSearchPanel', true);
			}
		},
  
		// 並び順変更
		changeSortNum(num){
			this.$store.commit('Pagination/setSort', num);
		},

		// ページネーションがクリックされたら、現在のページ数と求人更新
		changePage(page) {
			this.$store.commit('Pagination/setCurrentPage', page);
			this.changeUrl();
			if (this.$store.state.Pagination.isActiveSearchPanel === true) {
				this.$store.commit('Pagination/setIsActiveSearchPanel', false);
			}
		},

		// URL変更
		changeUrl(){
			// URL生成
			const url = new URL(window.location);
			const params = url.searchParams;

			// 検索パラメータ取得
			const industry = this.$store.state.Pagination.industry;
			const occupation = this.$store.state.Pagination.occupation;
			const employment_status = this.$store.state.Pagination.employment_status;
			const prefecture = this.$store.state.Pagination.prefecture;
			const annual_income = this.$store.state.Pagination.annual_income;
			const sort = this.$store.state.Pagination.sort;
			const page = this.$store.state.Pagination.current_page;

			// こだわり条件
			const commitmentsArray = this.$store.state.Pagination.commitments;
			let commitments = '';

			if(commitmentsArray.length > 0){
				let commitmentsParam = '';

				commitmentsArray.forEach(function(item){
					commitmentsParam += (item + '_');
				});
				// 末尾の1文字を削除
				commitments = commitmentsParam.slice( 0, -1 ) ;
			}
			
			// キーワード
			const str = this.$store.state.Pagination.keyword;
			let keyword = '';

			if(str !== null){
				// 先頭および末尾の空白と、行終端記号の文字を削除し、半角・全角の空白やタブなどを半角スペースに変換
				keyword = str.trim().replace(/\s+/g, ' ');
				this.$store.commit('Pagination/setKeyword', keyword);
			}

			// 検索条件があればセット、なければ削除
			(industry != 0) ? params.set("industry", industry) : params.delete("industry");
			(occupation != 0) ? params.set("occupation", occupation) : params.delete("occupation");
 			(employment_status != 0) ? params.set("employment_status", employment_status) : params.delete("employment_status");
			(prefecture != 0) ? params.set("prefecture", prefecture) : params.delete("prefecture");
			(annual_income != 0) ? params.set("annual_income", annual_income) : params.delete("annual_income");
			(sort != 1) ? params.set("sort", sort) : params.delete("sort");
			(commitments !== '') ? params.set("commitments", commitments) : params.delete("commitments");
			(keyword !== '') ? params.set("keyword", keyword) : params.delete("keyword");
			// 2ページ目以降はページ番号をセット
			(page != 1) ? params.set("page", page) : params.delete("page");

			// カルーセル表示判定
			this.showCarousel();

			// パス生成
			const path = this.$store.state.Pagination.baseUrl + url.search;
			// 同じパスに遷移するので、コールバック関数でエラー回避
			this.$router.push(path, () => {});
		},

		// カルーセル表示判定
		showCarousel(){
			if(this.searchRule){
				this.$store.commit('Pagination/setIsShowCarousel', false);
			}else{
				this.$store.commit('Pagination/setIsShowCarousel', true);
			}
		}
	},

	computed: {
		// csrf対策
		computedCsrf() {
		},

		// 入力データをバインディング
		occupation: {
			get() {
				return this.$store.state.Pagination.occupation;
			},
			set(num) {
				this.$store.commit('Pagination/setOccupation', num);
			}
		},
		industry: {
			get() {
				return this.$store.state.Pagination.industry;
			},
			set(num) {
				this.$store.commit('Pagination/setIndustry', num);
			}
		},
		employment_status: {
			get() {
				return this.$store.state.Pagination.employment_status;
			},
			set(num) {
				this.$store.commit('Pagination/setEmploymentStatus', num);
			}
		},
		prefecture: {
			get() {
				return this.$store.state.Pagination.prefecture;
			},
			set(num) {
				this.$store.commit('Pagination/setPrefecture', num);
			}
		},
		annual_income: {
			get() {
				return this.$store.state.Pagination.annual_income;
			},
			set(num) {
				this.$store.commit('Pagination/setAnnualIncome', num);
			}
		},
		keyword: {
			get() {
				return this.$store.state.Pagination.keyword;
			},
			set(str) {
				this.$store.commit('Pagination/setKeyword', str);
			}
		},
		sort: {
			get() {
				return this.$store.state.Pagination.sort;
			},
			set(num) {
				this.$store.commit('Pagination/setSort', num);
			}
		},

		// VueXのデータ取得
		isShowSidebar: {
			get() {
				return this.$store.state.Pagination.isShowSidebar;
			},
		},
		// ページネーション
		current_page: function () {
			return this.$store.state.Pagination.current_page;
		},
		page_range: function () {
			return this.$store.state.Pagination.page_range;
		},
		last_page: function () {
			return this.$store.state.Pagination.last_page;
		},

		// 検索テキスト
		searchRule(){
			let text = '';

			// 検索条件があればテキストに追加
			if(this.industry != 0){
				const id = Number(this.industry);
				const obj = this.industriesData.find((item) => item.id === id);
				text += (obj.name + ', ') ;
			}
			if(this.occupation != 0){
				const id = Number(this.occupation);
				const obj = this.occupationsData.find((item) => item.id === id);
				text += (obj.name + ', ') ;
			}
			if(this.employment_status != 0){
				const id = Number(this.employment_status);
				const obj = this.employment_statusData.find((item) => item.id === id);
				text += (obj.name + ', ') ;
			}
			if(this.prefecture != 0){
				const id = Number(this.prefecture);
				const obj = this.prefecturesData.find((item) => item.id === id);
				text += (obj.name + ', ') ;
			}
			if(this.annual_income != 0){
				text += ( String(150 + (this.annual_income * 50)) + '万円以上, ') ;
			}
			if(this.commitments.length > 0){
				this.commitments.forEach(function(item) {
					text += (item + ', ') ;
				});
			}
			if(this.keyword){
				let str = this.keyword;

				// 先頭および末尾の空白と、行終端記号の文字を削除し、半角・全角の空白やタブなどを「, 」に変換
				let newKeyword = str.trim().replace(/\s+/g, ', ');
				text += (newKeyword + ', ');
			}

			// 末尾の「, 」を削除
			const searchRuleText = text.slice(0, -2) ;
			return searchRuleText;
		},
	},
}
</script>
