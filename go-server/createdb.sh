CREATE TABLE "benchmark_result" (
    "benchmark_type"TEXT,
    "benchmark_result"REAL,
    "extra_info"TEXT,
    "machine_id"TEXT,
    "board"TEXT,
    "cpu_name"TEXT,
    "cpu_config"TEXT,
    "num_cpus"INTEGER,
    "num_cores"INTEGER,
    "num_threads"INTEGER,
    "memory_in_kib"INTEGER,
    "physical_memory_in_mib"INTEGER,
    "memory_types"TEXT,
    "opengl_renderer"TEXT,
    "gpu_desc"TEXT,
    "pointer_bits"INTEGER,
    "data_from_super_user"INTEGER,
    "used_threads"INTEGER,
    "benchmark_version"TEXT,
    "user_note"TEXT,
    "elapsed_time"REAL,
    "machine_data_version"INTEGER,
    "legacy"INTEGER,
    "machine_type"TEXT,
    "num_nodes"INTEGER,
    "timestamp"INTEGER
)

CREATE TABLE "cached_blobs" (
    "name"TEXT,
    "timestamp"INTEGER,
    "blob"BLOB DEFAULT (x''),
    PRIMARY KEY("name")
)
