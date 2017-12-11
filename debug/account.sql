-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-12-11 11:51:46
-- 服务器版本： 5.7.20
-- PHP Version: 7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easywechat`
--

-- --------------------------------------------------------

--
-- 表的结构 `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `openid` varchar(32) DEFAULT NULL,
  `usertype` int(11) DEFAULT '0' COMMENT '0 本科生',
  `sid` varchar(16) DEFAULT NULL,
  `idcard` varchar(8) DEFAULT NULL COMMENT '身份证后六位',
  `edu_passwd` varchar(32) DEFAULT NULL,
  `ecard_passwd` varchar(16) DEFAULT NULL,
  `nic_passwd` varchar(16) DEFAULT NULL,
  `lib_passwd` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `account`
--

INSERT INTO `account` (`id`, `openid`, `usertype`, `sid`, `idcard`, `edu_passwd`, `ecard_passwd`, `nic_passwd`, `lib_passwd`) VALUES
(1, 'oQ4KVw14cKQ4lucVr4N8mJNY_Cro', 0, '2015331250027', '252614', '19960815', '252614', '123456', '0000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `openid` (`openid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
