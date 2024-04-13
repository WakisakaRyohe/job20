<script>
import dayjs from 'dayjs'
import relativeTime from "dayjs/plugin/relativeTime"
import 'dayjs/locale/ja';

// 相対時間の有効化
dayjs.extend(relativeTime);
// ロケールを日本語に設定
dayjs.locale('ja');

export default {
	filters: {
		omittedText50(text) {
			return text.length > 50 ? text.slice(0, 49) + "…" : text;
		},
		omittedText100(text) {
			return text.length > 100 ? text.slice(0, 99) + "…" : text;
		},
		omittedText140(text) {
			return text.length > 140 ? text.slice(0, 139) + "…" : text;
		},
	},
    methods: {
		// 金額を３桁カンマに変換
        priceFormat(price) {
            return Number(price).toLocaleString();
        },
        // dayjsで日付を変換
        dateFormat(date) {
   		   return dayjs(date).format('YYYY年M月D日(dd)');
		},
		dateFormatMD(date) {
   		   return dayjs(date).format('M/D');
		},
		dateFormatHM(date) {
   		   return dayjs(date).format('HH:mm');
		},
		diff(date) {
   		   return dayjs().diff(dayjs(date), 'day');
		},
		diffDay(date) {
			const stringTo = dayjs().format('YYYY-MM-DD');
			const stringFrom = dayjs(date).format('YYYY-MM-DD');
			const to = dayjs(stringTo);
			const from = dayjs(stringFrom);
			const diff = from.diff(to, 'day', true);
			return diff;
		},
		// fromNow(date) {
   		//    return dayjs(date).fromNow();
		// },
    },
    computed:{
	},
}
</script>
