-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2020-08-10 08:53:40
-- サーバのバージョン： 10.4.13-MariaDB
-- PHP のバージョン: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `self_monitoring`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `color` int(11) NOT NULL COMMENT '色(2:黄 3:橙 4:赤 5:黒)',
  `activity` varchar(100) NOT NULL COMMENT '項目',
  `do_not_need` tinyint(4) NOT NULL COMMENT '表示なし  0:表示　1:非表示'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `activity`
--

INSERT INTO `activity` (`id`, `color`, `activity`, `do_not_need`) VALUES
(61, 2, '２０時には家に帰る', 0),
(62, 3, '土日ゆっくりする', 0),
(63, 4, '自宅療養', 0),
(64, 5, '入院する', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `condition_levels`
--

CREATE TABLE `condition_levels` (
  `id` int(11) NOT NULL,
  `monitoring_id` int(11) NOT NULL COMMENT 'セルフモニタリングID',
  `condition_id` int(11) NOT NULL COMMENT '体調ID',
  `condition_level` int(11) NOT NULL COMMENT '体調レベル'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `condition_levels`
--

INSERT INTO `condition_levels` (`id`, `monitoring_id`, `condition_id`, `condition_level`) VALUES
(857, 111, 1, 5),
(858, 111, 2, 2),
(859, 111, 3, 1),
(860, 111, 4, 1),
(861, 111, 5, 0),
(862, 111, 6, 0),
(863, 111, 7, 0),
(864, 111, 8, 2),
(865, 111, 9, 0),
(866, 112, 1, 2),
(867, 112, 2, 1),
(868, 112, 3, 0),
(869, 112, 4, 1),
(870, 112, 5, 2),
(871, 112, 6, 3),
(872, 112, 7, 4),
(873, 112, 8, 0),
(874, 112, 9, 1),
(875, 112, 10, 2),
(876, 112, 11, 0),
(877, 112, 12, 0),
(878, 113, 1, 4),
(879, 113, 2, 3),
(880, 113, 3, 2),
(881, 113, 4, 3),
(882, 114, 1, 4),
(883, 114, 2, 4),
(884, 114, 3, 4),
(885, 114, 4, 4),
(886, 114, 5, 4),
(887, 114, 6, 4),
(888, 114, 7, 4),
(889, 114, 8, 4),
(890, 114, 9, 4),
(891, 115, 1, 3),
(892, 115, 2, 3),
(893, 115, 3, 3),
(894, 115, 4, 3),
(895, 115, 5, 3),
(896, 115, 6, 3),
(897, 115, 7, 3),
(898, 115, 8, 3),
(899, 115, 9, 3),
(900, 116, 1, 2),
(901, 116, 2, 4),
(902, 116, 3, 0),
(903, 116, 4, 0),
(904, 116, 5, 0),
(905, 116, 6, 0),
(906, 116, 7, 0),
(907, 116, 8, 0),
(908, 116, 9, 0),
(909, 117, 1, 0),
(910, 117, 2, 1),
(911, 117, 3, 3),
(912, 117, 4, 1),
(913, 117, 5, 2),
(914, 117, 6, 4),
(915, 117, 7, 0),
(916, 118, 1, 0),
(917, 118, 2, 3),
(918, 118, 3, 3),
(919, 120, 1, 3),
(920, 120, 2, 2),
(921, 120, 3, 2),
(922, 120, 4, 0),
(923, 120, 5, 0),
(924, 120, 6, 2),
(925, 120, 7, 0),
(926, 120, 8, 0),
(927, 120, 9, 0),
(928, 120, 10, 0),
(929, 120, 11, 0),
(930, 120, 12, 0),
(931, 121, 1, 0),
(932, 121, 2, 2),
(933, 121, 3, 2),
(934, 121, 5, 2),
(935, 122, 1, 2),
(936, 122, 2, 2),
(937, 122, 3, 1),
(938, 122, 4, 2),
(939, 122, 5, 0),
(946, 124, 1, 5),
(947, 124, 2, 5),
(948, 124, 3, 0),
(949, 124, 4, 2),
(950, 124, 5, 0),
(951, 124, 6, 2),
(952, 124, 7, 0),
(953, 124, 8, 0),
(954, 124, 9, 0),
(955, 125, 1, 3),
(956, 125, 2, 3),
(957, 125, 3, 0),
(958, 125, 4, 0),
(959, 125, 5, 2),
(960, 125, 6, 3),
(961, 125, 7, 4),
(962, 125, 8, 0),
(963, 125, 9, 0),
(964, 125, 10, 2),
(965, 125, 11, 1),
(966, 125, 12, 0),
(967, 126, 1, 1),
(968, 126, 2, 1),
(969, 126, 6, 3),
(970, 126, 5, 2),
(971, 126, 11, 0),
(972, 126, 10, 0),
(973, 126, 12, 0),
(974, 127, 1, 1),
(975, 127, 5, 3),
(976, 127, 8, 1),
(977, 127, 7, 3),
(978, 127, 10, 1),
(979, 127, 12, 1),
(980, 127, 11, 1),
(981, 128, 3, 3),
(982, 128, 4, 4),
(983, 128, 5, 3),
(984, 128, 6, 3),
(985, 128, 7, 0),
(986, 128, 8, 0),
(987, 129, 3, 3),
(988, 129, 4, 1),
(989, 129, 5, 1),
(990, 123, 3, 2),
(991, 123, 4, 2),
(992, 130, 3, 3),
(993, 130, 6, 2),
(994, 130, 13, 1),
(995, 130, 14, 3),
(996, 130, 16, 3),
(997, 131, 1, 4),
(998, 131, 2, 1),
(999, 131, 3, 1),
(1000, 131, 4, 0),
(1001, 131, 5, 1),
(1002, 131, 6, 0),
(1003, 131, 7, 0),
(1004, 131, 8, 0),
(1005, 131, 9, 2),
(1006, 119, 3, 2),
(1007, 119, 6, 3);

-- --------------------------------------------------------

--
-- テーブルの構造 `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `weekday` int(11) NOT NULL COMMENT '曜日(0〜6:日〜土曜日)',
  `weekday_item` varchar(100) NOT NULL,
  `number` int(11) NOT NULL COMMENT '番号(0:1番目 1:2番目 2:3番目)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `event`
--

INSERT INTO `event` (`id`, `weekday`, `weekday_item`, `number`) VALUES
(26, 1, '未来のかたち', 1),
(27, 2, '未来のかたち', 1),
(28, 2, '', 2),
(29, 3, '', 3),
(30, 0, '', 3),
(31, 3, '未来のかたち', 1),
(32, 4, '未来のかたち', 1),
(33, 5, '未来のかたち', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `monitoring`
--

CREATE TABLE `monitoring` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_date_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '登録日時',
  `update_date_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '更新日時',
  `entries_date` date NOT NULL COMMENT '記録日',
  `weekday` int(11) NOT NULL COMMENT '曜日',
  `sleep_start_time` datetime DEFAULT NULL COMMENT '睡眠開始時間',
  `sleep_end_time` datetime DEFAULT NULL COMMENT '睡眠終了時間',
  `sleep_sum` time NOT NULL COMMENT '睡眠合計時間',
  `sound_sleep` tinyint(4) DEFAULT NULL COMMENT '朝起きた時の熟睡感 1:○　2:×　3:△',
  `nap` tinyint(4) DEFAULT NULL COMMENT '昼寝したか',
  `nap_start_time` datetime DEFAULT NULL COMMENT '昼寝開始時間',
  `nap_end_time` datetime DEFAULT NULL COMMENT '昼寝終了時間',
  `nap_sum` time NOT NULL COMMENT '昼寝合計時間',
  `spirit_signal` int(11) DEFAULT NULL COMMENT '体調・精神信号   0:青 1:緑 2:黄 3:橙 4:赤 5:黒',
  `weather` int(11) DEFAULT NULL COMMENT '天気',
  `event1` varchar(100) DEFAULT NULL COMMENT '出来事1',
  `event2` varchar(100) DEFAULT NULL COMMENT '出来事2',
  `event3` varchar(100) DEFAULT NULL COMMENT '出来事3',
  `notice` varchar(1000) DEFAULT NULL COMMENT '気づいたこと',
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1:削除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `monitoring`
--

INSERT INTO `monitoring` (`id`, `user_id`, `create_date_time`, `update_date_time`, `entries_date`, `weekday`, `sleep_start_time`, `sleep_end_time`, `sleep_sum`, `sound_sleep`, `nap`, `nap_start_time`, `nap_end_time`, `nap_sum`, `spirit_signal`, `weather`, `event1`, `event2`, `event3`, `notice`, `is_deleted`) VALUES
(111, 0, '2020-07-27 14:11:39', '2020-07-27 14:11:39', '2020-07-27', 0, '2020-07-27 00:30:00', '2020-07-27 09:00:00', '08:30:00', 3, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 1, 11, '未来のかたち', '', '', '朝少しイライラした', 0),
(112, 0, '2020-07-27 14:27:23', '2020-07-27 14:27:23', '2020-07-25', 0, '2020-07-27 00:00:00', '2020-07-27 08:00:00', '00:00:00', 2, 2, '2020-07-23 14:26:00', '2020-07-22 14:26:00', '00:00:00', 2, 6, '未来のかたち', '本', 'カフェ', 'しんどい', 0),
(113, 0, '2020-07-27 14:44:29', '2020-07-27 14:44:29', '2020-07-13', 0, '2020-07-27 00:00:00', '2020-07-27 08:00:00', '00:00:00', 2, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 2, 1, '', '', '', '', 0),
(114, 0, '2020-08-03 12:02:00', '2020-08-03 12:02:00', '2020-08-03', 0, '2020-08-03 00:00:00', '2020-08-03 08:00:00', '00:00:00', 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 2, 6, '', '', '', '', 1),
(115, 0, '2020-08-03 13:24:29', '2020-08-03 13:24:29', '2020-08-02', 0, '2020-08-02 01:00:00', '2020-08-02 09:00:00', '08:00:00', 1, 1, '2020-08-02 14:00:00', '2020-08-02 15:00:00', '01:00:00', 2, 3, '姪っ子たちと遊ぶ', '買い物', '料理', '爆睡した', 0),
(116, 0, '2020-08-04 10:51:00', '2020-08-04 10:51:00', '2020-08-04', 2, '2020-08-04 00:30:00', '2020-08-04 08:30:00', '08:00:00', 1, 1, '2020-08-05 14:49:00', '2020-08-05 15:33:00', '00:44:00', 0, 11, '未来のかたち', '姪っ子たちと遊ぶ', 'ゲーム', 'よく寝れた', 0),
(117, 0, '2020-08-04 11:50:15', '2020-08-04 11:50:15', '2020-07-31', 0, '2020-08-04 00:00:00', '2020-08-04 08:00:00', '08:00:00', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 2, 0, '', '', '', '', 0),
(118, 0, '2020-08-04 11:51:52', '2020-08-04 11:51:52', '2020-07-29', 3, '2020-08-04 00:00:00', '2020-08-04 03:00:00', '03:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 1, 0, '', '', '', '', 0),
(119, 0, '2020-08-05 13:09:18', '2020-08-05 13:09:18', '2020-08-05', 3, '2020-08-05 00:00:00', '2020-08-05 08:00:00', '08:00:00', 0, 0, '2020-08-05 12:09:00', '2020-08-05 13:09:00', '01:00:00', 3, 0, '', '', '', '', 0),
(120, 0, '2020-08-05 13:20:51', '2020-08-05 13:20:51', '2020-08-03', 1, '2020-08-05 00:00:00', '2020-08-05 08:00:00', '08:00:00', 3, 1, '2020-08-03 17:00:00', '2020-08-03 18:30:00', '01:30:00', 2, 11, '未来のかたち', '姪っ子たちと遊ぶ', '', '朝早く起きれてる', 0),
(121, 0, '2020-08-05 13:43:56', '2020-08-05 13:43:56', '2020-07-28', 2, '2020-08-05 00:00:00', '2020-08-05 08:00:00', '08:00:00', 0, 0, '2020-08-05 17:48:00', '2020-08-05 19:48:00', '02:00:00', 2, 0, '', '', '', '', 0),
(122, 0, '2020-08-05 14:29:10', '2020-08-05 14:29:10', '2020-07-30', 4, '2020-07-30 01:00:00', '2020-08-05 12:00:00', '11:00:00', 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 1, 13, '', '', '', '', 0),
(123, 0, '2020-08-06 09:46:13', '2020-08-06 09:46:13', '2020-08-06', 4, '2020-08-06 00:00:00', '2020-08-06 08:00:00', '08:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 2, 0, '', '', '', '', 0),
(124, 0, '2020-08-07 11:40:54', '2020-08-07 11:40:54', '2020-08-07', 5, '2020-08-06 23:30:00', '2020-08-07 08:00:00', '08:30:00', 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 2, 1, '未来のかたち', '姪っ子たちと遊ぶ', 'ゲーム', '勉強に対する余裕が出てきた', 0),
(125, 0, '2020-08-07 14:33:07', '2020-08-07 14:33:07', '2020-07-16', 4, '2020-07-16 01:00:00', '2020-07-16 09:00:00', '08:00:00', 1, 1, '2020-07-16 17:31:00', '2020-07-16 18:32:00', '01:01:00', 2, 12, '未来のかたち', '', '', '', 0),
(126, 0, '2020-08-07 14:36:30', '2020-08-07 14:36:30', '2020-07-17', 5, '2020-08-07 00:00:00', '2020-08-07 08:00:00', '08:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 2, 6, '', '', '', '', 0),
(127, 0, '2020-08-07 15:06:56', '2020-08-07 15:06:56', '2020-07-22', 3, '2020-08-07 00:00:00', '2020-08-07 08:00:00', '08:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 2, 7, '', '', '', '', 0),
(128, 0, '2020-08-10 10:43:36', '2020-08-10 10:43:36', '2020-08-08', 6, '2020-08-10 00:00:00', '2020-08-10 08:00:00', '08:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 3, 1, '', '', '', '', 0),
(129, 0, '2020-08-10 10:46:22', '2020-08-10 10:46:22', '2020-08-09', 0, '2020-08-10 00:00:00', '2020-08-10 08:00:00', '08:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 1, 0, '', '', '', '', 0),
(130, 0, '2020-08-10 10:47:41', '2020-08-10 10:47:41', '2020-08-10', 1, '2020-08-10 00:00:00', '2020-08-10 08:00:00', '08:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 4, 0, '', '', '', '', 0),
(131, 0, '2020-08-10 10:48:27', '2020-08-10 10:48:27', '2020-08-11', 2, '2020-08-10 00:00:00', '2020-08-10 08:00:00', '08:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 1, 11, '', '', '', '', 0),
(132, 0, '2020-08-10 14:02:20', '2020-08-10 14:02:20', '2020-08-13', 4, '2020-08-10 00:00:00', '2020-08-10 08:00:00', '08:00:00', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '00:00:00', 0, 0, '', '', '', '', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `physical_condition_items`
--

CREATE TABLE `physical_condition_items` (
  `id` int(11) NOT NULL,
  `item` varchar(50) NOT NULL COMMENT '項目',
  `short_name` varchar(5) NOT NULL COMMENT '略称',
  `display_unnecessary` tinyint(4) NOT NULL COMMENT '表示不要',
  `color` int(11) NOT NULL COMMENT '色(0:青 2:黄 6:追加黄 7:追加橙 8:追加赤)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `physical_condition_items`
--

INSERT INTO `physical_condition_items` (`id`, `item`, `short_name`, `display_unnecessary`, `color`) VALUES
(1, 'TV(漫画)が楽しめている', 'TV・漫画', 0, 0),
(2, '本が３０分読める', '本３０分', 0, 0),
(3, '眠い。欠伸する。', '眠い', 0, 2),
(4, 'やる気がない', 'やる気', 0, 2),
(5, '金遣いがルーズになる', '金遣い', 0, 2),
(6, '食欲が異常に湧く', '食欲', 0, 2),
(7, 'ニヤニヤが止まらない', 'ニヤニヤ', 0, 2),
(8, 'イライラ・モヤモヤ', 'イラモヤ', 0, 2),
(9, '幻聴、首のそわそわ', '幻聴・首', 0, 2),
(10, '頭に冷や汗をかく', '冷や汗', 0, 6),
(11, '貧乏ゆすり', '貧乏ゆすり', 0, 6),
(12, '髪の毛のセットが面倒くなる', '髪のセット', 0, 6),
(13, '頭痛', '頭痛', 0, 8),
(14, '吐き気', '吐き気', 0, 8),
(15, 'ボーとする', 'ボー', 0, 8),
(16, '心臓が痛い', '心臓が痛い', 0, 8);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `condition_levels`
--
ALTER TABLE `condition_levels`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `monitoring`
--
ALTER TABLE `monitoring`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `physical_condition_items`
--
ALTER TABLE `physical_condition_items`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- テーブルのAUTO_INCREMENT `condition_levels`
--
ALTER TABLE `condition_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1008;

--
-- テーブルのAUTO_INCREMENT `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- テーブルのAUTO_INCREMENT `monitoring`
--
ALTER TABLE `monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- テーブルのAUTO_INCREMENT `physical_condition_items`
--
ALTER TABLE `physical_condition_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
