CREATE TABLE `deals` (
  `id` bigint NOT NULL,
  `deal_id` bigint DEFAULT NULL,
  `provider` varchar(64) DEFAULT NULL,
  `sample_retrieve_cmd` varchar(512) DEFAULT NULL,
  `sample_request_cmd` varchar(512) DEFAULT NULL,
  `fetched` int DEFAULT NULL,
  `proposed` int DEFAULT NULL,
  `imported` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `miners` (
  `id` bigint NOT NULL,
  `provider` varchar(64) DEFAULT NULL,
  `ok` int DEFAULT NULL,
  `lasterror` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


ALTER TABLE `deals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deal_id` (`deal_id`),
  ADD KEY `provider` (`provider`),
  ADD KEY `fetched` (`fetched`),
  ADD KEY `imported` (`imported`),
  ADD KEY `proposed` (`proposed`);

ALTER TABLE `miners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provider_2` (`provider`),
  ADD KEY `provider` (`provider`),
  ADD KEY `ok` (`ok`);


ALTER TABLE `deals`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

ALTER TABLE `miners`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
