API Interface
-------------

The API interface is very simple - GET or POST to http://api.hardinfo2.org/benchmark.json

WIP - Please continue documenting the api interface, thanx.

GET - Example
-------------
Just go to http://api.hardinfo2.org/benchmark.json

POST Example
------------
```
{
  "CPU Blowfish (Single-thread)" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "7.0s, k:6eac709cca51a228bfa70150c9c5a7c4, d:c25cf5c889f7bead2ff39788eedae37b",
    "UserNote" : "",
    "BenchmarkResult" : 35.240000000000002,
    "ElapsedTime" : 7.0002560000000003,
    "UsedThreads" : 1,
    "BenchmarkVersion" : 1
  },
  "CPU Blowfish (Multi-thread)" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "7.0s, k:6eac709cca51a228bfa70150c9c5a7c4, d:c25cf5c889f7bead2ff39788eedae37b",
    "UserNote" : "",
    "BenchmarkResult" : 1273.53,
    "ElapsedTime" : 7.0117079999999996,
    "UsedThreads" : 64,
    "BenchmarkVersion" : 1
  },
  "CPU Blowfish (Multi-core)" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "7.0s, k:6eac709cca51a228bfa70150c9c5a7c4, d:c25cf5c889f7bead2ff39788eedae37b",
    "UserNote" : "",
    "BenchmarkResult" : 1275.01,
    "ElapsedTime" : 7.0083770000000003,
    "UsedThreads" : 64,
    "BenchmarkVersion" : 1
  },
  "CPU Zlib" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "zlib 1.2.11 (built against: 1.2.11), d:3753b649c4fa9ea4576fc8f89a773de2, e:0",
    "UserNote" : "",
    "BenchmarkResult" : 632.78999999999996,
    "ElapsedTime" : 7.0334940000000001,
    "UsedThreads" : 64,
    "BenchmarkVersion" : 3
  },
  "CPU CryptoHash" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "r:250, d:c25cf5c889f7bead2ff39788eedae37b",
    "UserNote" : "",
    "BenchmarkResult" : 10387.0,
    "ElapsedTime" : 5.0085660000000001,
    "UsedThreads" : 64,
    "BenchmarkVersion" : 2
  },
  "CPU Fibonacci" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "a:42",
    "UserNote" : "",
    "BenchmarkResult" : 1.0064360000000001,
    "ElapsedTime" : 1.0064360000000001,
    "UsedThreads" : 1,
    "BenchmarkVersion" : 0
  },
  "CPU N-Queens" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "q:11",
    "UserNote" : "",
    "BenchmarkResult" : 10.99601,
    "ElapsedTime" : 10.99601,
    "UsedThreads" : 10,
    "BenchmarkVersion" : 0
  },
  "FPU FFT" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "",
    "UserNote" : "",
    "BenchmarkResult" : 0.49346099999999998,
    "ElapsedTime" : 0.49346099999999998,
    "UsedThreads" : 4,
    "BenchmarkVersion" : -1
  },
  "FPU Raytracing" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "r:1000",
    "UserNote" : "",
    "BenchmarkResult" : 3.9486560000000002,
    "ElapsedTime" : 3.9486560000000002,
    "UsedThreads" : 64,
    "BenchmarkVersion" : 0
  },
  "SysBench CPU (Single-thread)" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "--time=7 --cpu-max-prime=10000",
    "UserNote" : "",
    "BenchmarkResult" : 4599.580078,
    "ElapsedTime" : 7.0002000000000004,
    "UsedThreads" : 1,
    "BenchmarkVersion" : 1000020
  },
  "SysBench CPU (Multi-thread)" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "--time=7 --cpu-max-prime=10000",
    "UserNote" : "",
    "BenchmarkResult" : 156683.203125,
    "ElapsedTime" : 7.0021000000000004,
    "UsedThreads" : 64,
    "BenchmarkVersion" : 1000020
  },
  "SysBench Memory (Single-thread)" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "--time=7 --memory-block-size=1K --memory-total-size=100G --memory-scope=global --memory-hugetlb=off --memory-oper=write --memory-access-mode=seq",
    "UserNote" : "",
    "BenchmarkResult" : 7438.3398440000001,
    "ElapsedTime" : 7.0000999999999998,
    "UsedThreads" : 1,
    "BenchmarkVersion" : 1000020
  },
  "SysBench Memory" : {
    "Board" : "Intel 440BX Desktop Reference Platform (VMware VMware Virtual Platform)",
    "MemoryInKiB" : 32846368,
    "CpuName" : "AMD EPYC 9354P",
    "CpuDesc" : "1 physical processor; 64 cores; 64 threads",
    "CpuConfig" : "64x 3250.03 MHz",
    "OpenGlRenderer" : "llvmpipe (LLVM 15.0.7, 256 bits)",
    "GpuDesc" : "VMware SVGA II Adapter",
    "NumCpus" : 1,
    "NumCores" : 64,
    "NumNodes" : 1,
    "NumThreads" : 64,
    "MachineId" : "Intel_440BX_Desktop_Reference_Platform_(VMware_VMware_Virtual_Platform);AMD_EPYC_9354P;208001_92",
    "PointerBits" : 64,
    "DataFromSuperUser" : true,
    "PhysicalMemoryInMiB" : 32768,
    "MemoryTypes" : "RAM",
    "MachineDataVersion" : 0,
    "MachineType" : "Virtual (VMware)",
    "Legacy" : false,
    "ExtraInfo" : "--time=7 --memory-block-size=1K --memory-total-size=100G --memory-scope=global --memory-hugetlb=off --memory-oper=write --memory-access-mode=seq",
    "UserNote" : "",
    "BenchmarkResult" : 8235.1904300000006,
    "ElapsedTime" : 7.0002000000000004,
    "UsedThreads" : 4,
    "BenchmarkVersion" : 1000020
  }
}
```
