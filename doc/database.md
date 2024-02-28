Database - MariaDB
------------------

WIP - Please continue documenting the database, thanx.

Settings Table
-----------------
```
CREATE TABLE `settings` (
  `name` varchar(30) NOT NULL,
    `value` blob NOT NULL,
      PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
```

*settings*
dbver - this will be used to upgrade the database via github.
github-refresh - timer for max 1 per hour request for release information
github-releasetxt - the cached releasetxt from github


Incoming Benchmark (results from JSON) Table
--------------------------------------
```
CREATE TABLE `benchmark_result` (
  `benchmark_type` text DEFAULT NULL,
  `benchmark_result` double DEFAULT NULL,
  `extra_info` text DEFAULT NULL,
  `machine_id` text DEFAULT NULL,
  `board` text DEFAULT NULL,
  `cpu_name` text DEFAULT NULL,
  `cpu_config` text DEFAULT NULL,
  `num_cpus` int(11) DEFAULT NULL,
  `num_cores` int(11) DEFAULT NULL,
  `num_threads` int(11) DEFAULT NULL,
  `memory_in_kib` int(11) DEFAULT NULL,
  `physical_memory_in_mib` int(11) DEFAULT NULL,
  `memory_types` text DEFAULT NULL,
  `opengl_renderer` text DEFAULT NULL,
  `gpu_desc` text DEFAULT NULL,
  `pointer_bits` int(11) DEFAULT NULL,
  `data_from_super_user` int(11) DEFAULT NULL,
  `used_threads` int(11) DEFAULT NULL,
  `benchmark_version` int(11) DEFAULT NULL,
  `user_note` text DEFAULT NULL,
  `elapsed_time` double DEFAULT NULL,
  `machine_data_version` int(11) DEFAULT NULL,
  `legacy` int(11) DEFAULT NULL,
  `num_nodes` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `machine_type` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
```

Outgoing Benchmark (results to JSON) Table
-----------------------------------
Will be pulled from the same table for a start
