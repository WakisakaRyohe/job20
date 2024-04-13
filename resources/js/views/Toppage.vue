<template>
	<!-- <div class="p-toppage c-inner--l"> -->
	<div>
		<!-- ロード中 -->
		<Loading v-if="isLoading"></Loading>

		<div v-else ref="page">
			<!-- ファーストビュー -->
			<section class="c-topSection--bgMain p-topFirstView" ref="firstView">
				<div class="c-topSection__container p-topFirstView__container">
					<div class="c-topSection__content p-topFirstView__content">
						<!-- キャッチコピー -->
						<div class="p-topFirstView__copyArea">
							<div class="p-topFirstView__copyHead" 
								:class="[ {slide: visibleFirstView}, {slideVertical: visibleFirstView}, {showCopyHead: visibleFirstView} ]">
								20代専門の総合転職サイト
							</div>
							<div class="p-topFirstView__copyBody" 
								:class="[ {slide: visibleFirstView}, {slideBeside: visibleFirstView}, {showCopyBody: visibleFirstView} ]">
								<div class="p-topFirstView__copyLine">ジョブインフォ20が</div>
								<div class="p-topFirstView__copyLine">
									<span class="p-topFirstView__maker">20代•第二新卒</span>の<br v-if="isWidthL">
									<span class="p-topFirstView__maker--2">転職成功</span>を
								</div>
								<div class="p-topFirstView__copyLine">徹底サポートします。</div>
								<!-- 会員登録ボタン -->
								<router-link v-if="!isWidthM" :to="`/job20/register`" class="c-topClosing__btn p-topFirstView__btn">
									<span class="c-topClosing__btnText">無料で会員登録する</span>
								</router-link>
							</div>
						</div>
						<!-- 画像 -->
						<div class="p-topFirstView__imageArea">
							<div v-if="isWidthM" class="p-topFirstView__btnArea">
								<!-- 会員登録ボタン -->
								<router-link :to="`/job20/register`" class="c-topClosing__btn--circle" 
									:class="[ {slide: visibleFirstView}, {slideBeside: visibleFirstView}, {showCopyBody: visibleFirstView} ]">
									<i class="fa-solid fa-user"></i>
									<div class="c-topClosing__btnText">
										<div>無料で</div><div>会員登録</div>
									</div>
								</router-link>
							</div>
							<img v-if="isWidthL" class="p-topFirstView__image" :src="firstViewImgPathLg" :alt="'ファーストビュー画像'">
							<img v-else class="p-topFirstView__image" :src="firstViewImgPath" :alt="'ファーストビュー画像'">
						</div>
					</div>
				</div>
				<!-- フッター -->
				<div class="p-topFirstView__footer">
					<ul class="c-topSection__cards p-topFirstView__cards">
						<li class="p-topFirstView__card" 
							:class="[ {slide: visibleFirstView}, {slideVertical: visibleFirstView}, {showCard: visibleFirstView} ]" 
							v-for="(achievement, index) in achievements" :key="index" v-if="index < 3">
							<div class="p-topFirstView__icon" v-html="achievement.icon"></div>
							<div class="p-topFirstView__cardTitle">{{ achievement.title }}</div>
							<div class="p-topFirstView__dataArea">
								<span class="p-topFirstView__num">{{ achievement.num }}</span><br class="u-tabShow">
								<span class="p-topFirstView__text">{{ achievement.text }}</span>
							</div>
						</li>
					</ul>
				</div>
			</section>

			<!-- 悩み -->
			<section class="c-topSection--bgSub p-topWorry" ref="worry">
				<div class="c-topSection__container" :class="[ {slide: visibleWorry}, {slideVertical: visibleWorry} ]">
					<h2 class="c-topSection__titleArea">
						<span class="c-topSection__titleText">
							<template v-if="isWidthS">
								<div class="u-mb5">
									こんな<span class="p-topWorry__maker"><span>お</span><span>悩</span><span>み</span></span>は
								</div>
								ありませんか？
							</template>
							<template v-else>
								こんな<span class="p-topWorry__maker"><span>お</span><span>悩</span><span>み</span></span>はありませんか？
							</template>
						</span>
					</h2>
					<div class="c-topSection__content">
						<ul class="c-topSection__cards">
							<li class="c-topSection__card p-topWorry__card" :class="[ {slide: visibleWorry}, {slideVertical: visibleWorry}, {show: visibleWorry} ]" v-for="(worry, index) in worries" :key="index">
								<figure class="p-topWorry__img">
									<img :src="worryImgPath + (index+1) + '.png'" :alt="'お悩み画像' + (index+1)">
								</figure>
								<p class="p-topWorry__cardText" v-html="worry"></p>
							</li>
						</ul>
					</div>
				</div>
			</section>

			<!-- 当サイトが解決します！、選ばれる理由（サイトの特徴） -->
			<section class="c-topSection--bgMain p-topSolution" ref="solution">
				<div class="c-topSection__container" :class="[ {slide: visibleSolution}, {slideVertical: visibleSolution} ]">
					<h2 class="c-topSection__titleArea">
						<template v-if="isWidthS">
							<span class="c-topSection__titleText p-topSolution__titleText">ジョブインフォ20が</span><br>
							<span class="c-topSection__titleText p-topSolution__titleText">解決します!</span>
						</template>
						<span v-else class="c-topSection__titleText p-topSolution__titleText">ジョブインフォ20が解決します!</span>
					</h2>
					<div class="c-topSection__content">
						<div class="p-topSolution__about">
							ジョブインフォ20は、20代や第二新卒・既卒者の転職サポートに特化し、<br v-if="isWidthM">20代の転職事情を熟知しております。<br>
							転職の不安を気軽に相談できる専任アドバイザーが、<br v-if="isWidthM">1対1であなたの就活を徹底サポートします。
						</div>
						<ul class="c-topSection__cards">
							<li class="c-topSection__card p-topSolution__card" :class="[ {slide: visibleSolution}, {slideVertical: visibleSolution}, {show: visibleSolution} ]" 
								v-for="(solution, index) in solutions" :key="index">
								<div v-if="isWidthM" class="p-topSolution__cardUnit">
									<div class="p-topSolution__cardSubUnit">
										<div class="p-topSolution__label">
											<span class="p-topSolution__badge" :class="{rotate: visibleSolution}">
												<span class="p-topSolution__point">POINT</span>
												<span class="p-topSolution__num">0{{ index+1 }}</span>
											</span>
											<span class="p-topSolution__cardTitle">{{ solution.title }}</span>
										</div>
										<p class="p-topSolution__cardText" v-html="solution.text"></p>
									</div>
									<div class="p-topSolution__imgArea">
										<img class="p-topSolution__img" :src="solutionImgPath + (index+1) + '.jpg'" :alt="'解決策画像' + (index+1)">
									</div>
								</div>
								<template v-else>
									<div class="p-topSolution__label">
										<span class="p-topSolution__badge" :class="{rotate: visibleSolution}">
											<span class="p-topSolution__point">POINT</span>
											<span class="p-topSolution__num">0{{ index+1 }}</span>
										</span>
										<span class="p-topSolution__cardTitle">{{ solution.title }}</span>
									</div>
									<div class="p-topSolution__imgArea">
										<img class="p-topSolution__img" :src="solutionImgPath + (index+1) + '.jpg'" :alt="'解決策画像' + (index+1)">
									</div>
									<p class="p-topSolution__cardText" v-html="solution.text"></p>
								</template>
							</li>
						</ul>
					</div>
				</div>
			</section>

			<!-- 実績 -->
			<section class="c-topSection--bgSub p-topAchievement" ref="achievement">
				<div class="c-topSection__container p-topAchievement__container" :class="[ {slide: visibleAchievement}, {slideVertical: visibleAchievement} ]">
					<h2 class="c-topSection__titleArea p-topAchievement__titleArea">
						<span class="c-topSection__titleText p-topAchievement__titleText">ジョブインフォ20は、</span><br>
						<template v-if="isWidthXL || isWidthL">
							<span class="c-topSection__titleText p-topAchievement__titleText">
								転職サポートで<span class="p-topAchievement__maker">確かな実績</span>を残しています。
							</span>
						</template>
						<template v-if="isWidthM || isWidthS">
							<span class="c-topSection__titleText p-topAchievement__titleText">転職サポートで<span class="p-topAchievement__maker">確かな実績</span>を</span><br>
							<span class="c-topSection__titleText p-topAchievement__titleText">残しています。</span>
						</template>
					</h2>
					<div class="c-topSection__content u-mb20">
						<ul class="c-topSection__cards p-topAchievement__cards">
							<li class="c-topSection__card p-topAchievement__card" 
								:class="[ {slide: visibleAchievement}, {slideVertical: visibleAchievement }, {show: visibleAchievement} ]" 
								v-for="(achievement, index) in achievements" :key="index">
								<div class="p-topAchievement__cardTitle">{{ achievement.title }}</div>
								<div class="p-topAchievement__dataArea">
									<template v-if="isWidthM || isWidthS">
										<span class="p-topAchievement__num">
											<template v-if="achievement.num2">{{ achievement.num2 }}</template>
											<template v-else>{{ achievement.num }}</template>
										</span>
										<span class="p-topAchievement__text">
											<template v-if="achievement.text2">{{ achievement.text2 }}</template>
											<template v-else>{{ achievement.text }}</template>
											<span class="c-attention p-topAchievement__attentionNum">{{ achievement.attentionNum }}</span>
										</span>
									</template>
									<template v-else>
										<span class="p-topAchievement__num">{{ achievement.num }}</span>
										<span class="p-topAchievement__text">{{ achievement.text }}
											<span class="c-attention p-topAchievement__attentionNum">{{ achievement.attentionNum }}</span>
										</span>
									</template>
								</div>
							</li>
						</ul>
					</div>
					<div class="p-topAchievement__attentionArea"">
						<ul>
							<li>* 1 : 2024年2月時点</li>
							<li>* 2 : 転職支援サービス「ジョブインフォ20」の実施年数</li>
							<li>* 3 : 2022/3/17～2023/4/20の相談者のうち相談に満足された方の割合</li>
						</ul>
					</div>
				</div>
			</section>

			<!-- 利用者の声 -->
			<section class="c-topSection p-topReview" ref="review">
				<div class="c-topSection__container" :class="[ {slide: visibleReview}, {slideVertical: visibleReview} ]">
					<h2 class="c-topSection__titleArea">
						<span class="c-topSection__titleText">ジョブインフォ20<br class="u-spShow">ご利用者の声</span>
					</h2>
					<div class="c-topSection__content">
						<ul class="c-topSection__cards p-topReview__cards">
							<li class="c-topSection__card p-topReview__card" 
								:class="[ {slide: visibleReview}, {slideVertical: visibleReview && (isWidthXL || isWidthL) }, 
								{slideBeside: visibleReview && (isWidthM || isWidthS) }, {show: visibleReview} ]" 
								v-for="(review, index) in reviews" :key="index">
								<div class="p-topReview__review" v-html="review.review"></div>
								<div class="p-topReview__dataArea">
									<div class="p-topReview__userData">
										<img class="p-topReview__img" :src="reviewImgPath + (index+1) + '.png'" :alt="'レビュー画像' + (index+1)">
										<div class="p-topReview__age">{{ review.age }}</div>
									</div>
									<div class="p-topReview__occupationData">
										<div class="p-topReview__occupationBefore">
											<span class="p-topReview__badge--before"><span class="p-topReview__badgeText">転職前</span></span>
											<span class="p-topReview__occupationName">{{ review.occupationBefore }}</span>
										</div>
										<span class="p-topReview__triangle"></span>
										<div class="p-topReview__occupationAfter">
											<span class="p-topReview__badge--after"><span class="p-topReview__badgeText">転職後</span></span>
											<span class="p-topReview__occupationName">{{ review.occupationAfter }}</span>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</section>

			<!-- 新着求人（カルーセル） -->
			<section class="c-topSection--bgMain p-topNewJobs" ref="newJobs">
				<div class="c-topSection__container" :class="[ {slide: visibleNewJobs}, {slideVertical: visibleNewJobs} ]">
					<h2 class="c-topSection__titleArea">
						<span class="c-topSection__titleText">新着求人の<br v-if="isWidthS">一部をご紹介</span>
					</h2>
					<div class="c-topSection__content">
						<!-- カルーセル -->
						<Carousel :jobList="jobs" :topFlg="true"></Carousel>
					</div>
				</div>
			</section>

			<!-- ご利用の流れ -->
			<section class="c-topSection--bgSub p-topFlow" ref="flow">
				<div class="c-topSection__container" :class="[ {slide: visibleFlow}, {slideVertical: visibleFlow} ]">
					<h2 class="c-topSection__titleArea p-topFlow__titleArea">
						<span class="c-topSection__titleText">ご利用の流れ</span>
					</h2>
					<div class="c-topSection__content">
						<ul v-if="!isWidthL" class="c-topSection__cards p-topFlow__cards">
							<li class="c-topSection__card p-topFlow__card" 
								:class="[ {slide: visibleFlow}, {slideVertical: visibleFlow && isWidthXL}, {slideBeside: visibleFlow && (isWidthM || isWidthS) }, 
								{show: visibleFlow}, {'p-topFlow__card--line1': isWidthXL}  ]"
								v-for="(flow, index) in flows" :key="index">
								<div class="p-topFlow__imgArea">
									<span class="p-topFlow__badge">{{ index+1 }}</span>
									<div class="p-topFlow__imgBg">
										<div class="p-topFlow__img" v-html="flow.icon"></div>
									</div>
								</div>
								<div class="p-topFlow__textArea">
									<div class="p-topFlow__title">{{ flow.title }}</div>
									<div class="p-topFlow__text">{{ flow.text }}</div>
								</div>
							</li>
						</ul>
						<template v-if="isWidthL">
							<ul class="c-topSection__cards p-topFlow__cards u-mb30">
								<li class="c-topSection__card p-topFlow__card--line1" 
									:class="[ {slide: visibleFlow}, {slideVertical: visibleFlow}, {show: visibleFlow} ]"
									v-for="(flow, index) in flows" :key="index" v-if="index <= 2">
									<div class="p-topFlow__imgArea">
										<span class="p-topFlow__badge">{{ index+1 }}</span>
										<div class="p-topFlow__imgBg">
											<div class="p-topFlow__img" v-html="flow.icon"></div>
										</div>
									</div>
									<div class="p-topFlow__textArea">
										<div class="p-topFlow__title">{{ flow.title }}</div>
										<div class="p-topFlow__text">{{ flow.text }}</div>
									</div>
								</li>
							</ul>
							<ul class="c-topSection__cards p-topFlow__cards">
								<li class="c-topSection__card p-topFlow__card" 
									:class="[ {slide: visibleFlow}, {slideVertical: visibleFlow}, {showLine2: visibleFlow} ]"
									v-for="(flow, index) in flows" :key="index" v-if="index > 2">
									<div class="p-topFlow__imgArea">
										<span class="p-topFlow__badge">{{ index+1 }}</span>
										<div class="p-topFlow__imgBg">
											<div class="p-topFlow__img" v-html="flow.icon"></div>
										</div>
									</div>
									<div class="p-topFlow__textArea">
										<div class="p-topFlow__title">{{ flow.title }}</div>
										<div class="p-topFlow__text">{{ flow.text }}</div>
									</div>
								</li>
							</ul>
						</template>
					</div>
				</div>
			</section>

			<!-- よくある質問 -->
			<section class="c-topSection p-topFaq" ref="faq">
				<div class="c-topSection__container p-topFaq__container" :class="[ {slide: visibleFaq}, {slideVertical: visibleFaq} ]">
					<h2 class="c-topSection__titleArea">
						<span class="c-topSection__titleText--ornament p-topFaq__titleText">よくある質問</span>
					</h2>
					<div class="c-topSection__content">
						<ul class="c-topSection__cards p-topFaq__cards">
							<li class="c-topSection__card p-topFaq__card" 
								:class="[ {slide: visibleFaq}, {slideBeside: visibleFaq}, {show: visibleFaq} ]"
								v-for="(faq, index) in faqs" :key="index">
								<Accordion :topFlg="true">
									<div class="p-topFaq__question" slot="title">{{ faq.question }}</div>
									<div class="p-topFaq__answer" slot="detail">{{ faq.answer }}</div>
								</Accordion>
							</li>
						</ul>
					</div>
				</div>
			</section>		
			
			<!-- 会員登録ボタン -->
			<div class="c-topClosing--hidden" :class="[ {slide: scrollY > 300 && isShowBtn}, {slideVertical: scrollY > 300 && isShowBtn} ]">
				<router-link :to="`/job20/register`" class="c-topClosing__btn">
					<span class="c-topClosing__btnText">無料で会員登録する</span>
				</router-link>
			</div>
		</div>
	</div>
</template>

<script>
import Accordion from '../components/Accordion.vue';
import Carousel from '../components/Carousel.vue';
import HandleResize from '../mixin/HandleResize.vue';
// import JobInfoProcessing from '../mixin/JobInfoProcessing.vue';
import Loading from '../components/Loading.vue';

export default {
	components: {
		"Accordion": Accordion,
		"Carousel": Carousel,
		"Loading": Loading,
	},
	mixins: [ HandleResize ],
	data(){
		return {
			jobs: [],
			firstViewImgPath: 'https://d38rk2cibjrg07.cloudfront.net/job_change_20/top_firstView.png',
			firstViewImgPathLg: 'https://d38rk2cibjrg07.cloudfront.net/job_change_20/top_firstView_lg.png',
			worryImgPath: 'https://d38rk2cibjrg07.cloudfront.net/job_change_20/worry_image',
			solutionImgPath: 'https://d38rk2cibjrg07.cloudfront.net/job_change_20/solution_image',
			reviewImgPath: 'https://d38rk2cibjrg07.cloudfront.net/job_change_20/review_image',
			worries: [
				'初めての転職で、<br>分からないことが多すぎる',
				'働きやすい職場に<br>転職できるかどうか不安',
				'転職したいけど、<br>20代後半だと厳しそう',
				// '自分に合った<br>仕事がわからない',
				// '20代の未経験で<br>応募できる求人を探している',
				// '職務経歴書・履歴書の書き方や、<br>面接練習などをサポートしてほしい',
				// '転職サイトに登録したけど、<br>希望通りの求人が見つからない',
			],
			solutions: [
				{
					title: '豊富な求人',
					text: '<span class="p-topSolution__maker">有名企業からIT・ベンチャー企業の求人</span>まで、20代の未経験で応募できる求人が多数',
				},
				{
					title: '定着率',
					text: 'ご紹介する企業を専属のアドバイザーが隅から隅まで調査し、<span class="p-topSolution__maker">入社後のギャップをゼロ</span>に近づけます。',
				},
				{
					title: '完全無料',
					text: 'お仕事紹介や応募書類の添削、面接対策など求職者の方は<span class="p-topSolution__maker">すべてのサービスを完全無料</span>でご利用いただけます。',
				},
			],
			reviews: [
				{
					review: '<span class="p-topReview__maker">未経験OKの職種がとても多く求人が豊富</span>でたくさん選べるのが良いと思いました。<br>面接練習を何度も行って下さり、親身になって自分の魅力を伝える方法をアドバイスしてくれました。',
					age: '26歳/女性',
					occupationBefore: '営業',
					occupationAfter: 'ITコンサルタント',
				},
				{
					review: '<span class="p-topReview__maker">自分では探せないような大手の会社</span>にも面接に行けたので、やはりお願いして間違いじゃなかったと確信しました。<br>イチからどういう手順で進めるかを説明していただけて苦労することなく活動できたと思います。',
					age: '29歳/男性',
					occupationBefore: 'スーパーバイザー',
					occupationAfter: '医療営業',
				},
				{
					review: '他の転職エージェントも利用していましたが、ジョブインフォ20が一番使いやすかったです。<br><span class="p-topReview__maker">カウンセラーの方も親身に話を聞いてくれて転職に成功しました。</span>',
					age: '27歳/男性',
					occupationBefore: '販売',
					occupationAfter: 'マーケティング・PR',
				},
			],
			achievements: [
				{
					title: '登録者数',
					num: '20',
					text: '万人以上',
					attentionNum: '*1',
					icon: '<i class="fas fa-user"></i>',
				},
				{
					title: '運営歴',
					num: '15',
					text: '年以上',
					attentionNum: '*2',
					icon: '<i class="fas fa-mobile-alt"></i>',
				},
				{
					title: '面談満足度',
					num: '94.8',
					text: '%',
					attentionNum: '*3',
					icon: '<i class="fas fa-chart-pie"></i>',
				},
				{
					title: '求人数',
					num: '50,000',
					num2: '5',
					text: '件以上',
					text2: '万件以上',
					attentionNum: '*1',
				},
			],
			flows: [
				{
					icon: '<i class="fa-solid fa-mobile-screen-button"></i>',
					title: 'お申し込み',
					text: 'サイト・スマホから1分で申込み完了！',
				},
				{
					icon: '<i class="fa-solid fa-comments"></i>',
					title: 'カウンセリング',
					text: '転職コンシェルジュによる詳細なカウンセリング。',
				},
				{
					icon: '<i class="fa-solid fa-table-list"></i>',
					title: '求人紹介',
					text: 'あなたの希望やスキルを基に求人をご紹介。',
				},
				{
					icon: '<i class="fa-solid fa-user-pen"></i>',
					title: '応募・面接',
					text: '応募書類の添削や模擬面接など手厚くサポート。',
				},
				{
					icon: '<i class="fa-solid fa-handshake"></i>',
					title: '内定・入社',
					text: '内定後の年収交渉、入社後のアフターフォローも万全。',
				},
			],
			faqs: [
				{
					question: 'サービスの利用にお金はかかりますか？',
					answer: 'サービスの利用はすべて無料です。皆様からお金をいただくことはありませんので、気軽にご利用ください。',
				},
				{
					question: '初めての転職で何から始めたらいいかわかりません。',
					answer: '求人が多すぎて何が自分に合っているかわからない、提出書類の書き方や面接に不安があるなどの不安や悩みに、転職コンシェルジュが寄り添い、お手伝いします。まずはお気軽に無料転職相談にご登録ください。',
				},
				{
					question: '最短どれくらいで就職が決まりますか？',
					answer: 'スケジュールや選考の進み具合にもよりますが、最短で2週間～1か月程度での就職が可能です。',
				},
				{
					question: '就職活動をしていますがなかなか内定が出ません。それでも大丈夫ですか？',
					answer: 'もちろん大丈夫です！なかなか内定が出ず、一人で悩んでいる方にこそ活用して頂きたいです。',
				},
				{
					question: '他の就職サービスとは何が違いますか？',
					answer: '20代未経験者に特化しているため、実践的な面接練習や基礎講義を活用いただけます。',
				},
			],
			isLoading: true,
			// スクロール量を検知
			scrollY: 0,
			// フェードイン判定フラグ
			visibleFirstView: false,
			visibleWorry: false,
			visibleSolution: false,
			visibleNewJobs: false,
			visibleAchievement: false,
			visibleReview: false,
			visibleFlow: false,
			visibleFaq: false,
			// クロージングボタン表示フラグ
			isShowBtn: false,
		}
	},

	methods:{
		// スクロール量を検知
		handleScroll() {
			this.scrollY = window.scrollY;

			// お悩みセクション表示
			if( this.$refs.worry.getBoundingClientRect().top < (window.innerHeight / 3) && 
				this.visibleWorry === false ){
				this.visibleWorry = true;
			}

			// 解決セクション表示
			if( this.$refs.solution.getBoundingClientRect().top < (window.innerHeight / 3) && 
				this.visibleSolution === false ){
				this.visibleSolution = true;
			}

			// 実績セクション表示
			if( this.$refs.achievement.getBoundingClientRect().top < (window.innerHeight / 3) && 
				this.visibleAchievement === false ){
				this.visibleAchievement = true;
			}

			// レビューセクション表示
			if( this.$refs.review.getBoundingClientRect().top < (window.innerHeight / 3) && 
				this.visibleReview === false ){
				this.visibleReview = true;
			}

			// 新着求人セクション表示
			if( this.$refs.newJobs.getBoundingClientRect().top < (window.innerHeight / 3) && 
				this.visibleNewJobs === false ){
				this.visibleNewJobs = true;
			}

			// ご利用の流れセクション表示
			if( this.$refs.flow.getBoundingClientRect().top < (window.innerHeight / 3) && 
				this.visibleFlow === false ){
				this.visibleFlow = true;
			}

			// よくある質問セクション表示
			if( this.$refs.faq.getBoundingClientRect().top < (window.innerHeight / 3) && 
				this.visibleFaq === false ){
				this.visibleFaq = true;
			}

			// フッターまでスクロールしたら、クロージングボタン非表示
			if( this.$refs.faq.getBoundingClientRect().bottom < (window.innerHeight + 100) ){
				this.isShowBtn = false;
			}else{
				this.isShowBtn = true;
			}
		},

		// 新着求人を取得
		getNewJobs() {
			// axiosで使うthisを格納
			const self = this;

			axios.get('/job20/web/toppage')
			.then(response => {
				if(response.data.successFlg){
					self.jobs = response.data.jobs;
					self.isLoading = false;
				}else{
					self.$router.push({ name: 'error' });	
				}
			});
		},
	},

	computed: {
	},

	mounted(){
		// トップに戻るボタンの位置変更
		this.$store.commit('GoTop/setBottom', 90);
		// 新着求人を取得
		this.getNewJobs();
		// イベント設定
		window.addEventListener("scroll", this.handleScroll);
	},

	updated(){
		// フェードイン開始
		const self = this;
		setTimeout(function(){
			self.visibleFirstView = true;
		}, 500);
	},

	beforeDestroy() {
		// トップに戻るボタンの位置変更
		this.$store.commit('GoTop/setBottom', 20);	
		//EventListener削除
		window.removeEventListener("scroll", this.handleScroll);
	},
}
</script>

<style scoped>
/* スライド共通 */
.slide{
	opacity: 1;
	transition: all .5s;
}
/* 縦スライド */
.slideVertical{
	transform: translateY(0);
}
/* 横スライド */
.slideBeside{
	transform: translateX(0);
}

/* ファーストビュー */
.showCopyHead{
	transition-delay: .3s;
}
.showCopyBody{
	transition-delay: .6s;
}
.slide.showCard:nth-of-type(1) {
	transition-delay: .9s;
}
.slide.showCard:nth-of-type(2) {
	transition-delay: 1.1s;
}
.slide.showCard:nth-of-type(3) {
	transition-delay: 1.3s;
}

/* 解決セクションの回転 */
.rotate{
	animation: rotate 4s linear 3s infinite normal;
}
.p-topSolution__card:nth-of-type(2) .rotate {
	animation-delay: 4s;
}
.p-topSolution__card:nth-of-type(3) .rotate {
	animation-delay: 5s;
}
@keyframes rotate {
	0% {
		transform: rotateY(0deg);
	}
	25%{
		transform: rotateY(360deg);
	}
	100%{
		transform: rotateY(360deg);
	}
}

/* 縦スライドのみの子要素 */
.slide.show:nth-of-type(1) {
  transition-delay: 0.2s;
}
.slide.show:nth-of-type(2) {
  transition-delay: 0.4s;
}
.slide.show:nth-of-type(3) {
  transition-delay: 0.6s;
}
.slide.show:nth-of-type(4) {
  transition-delay: 0.8s;
}
.slide.show:nth-of-type(5) {
  transition-delay: 1s;
}
/*ご利用の流れ２段目 */
.slide.showLine2:nth-of-type(1) {
  transition-delay: 0.8s;
}
.slide.showLine2:nth-of-type(2) {
  transition-delay: 1s;
}

/* 親が縦スライドで、横スライドする子要素 */
.slide.show:nth-of-type(1) {
  transition-delay: 0.5s;
}
.slide.show:nth-of-type(2) {
  transition-delay: 0.7s;
}
.slide.show:nth-of-type(3) {
  transition-delay: 0.9s;
}
.slide.show:nth-of-type(4) {
  transition-delay: 1.1s;
}
.slide.show:nth-of-type(5) {
  transition-delay: 1.3s;
}
</style>
