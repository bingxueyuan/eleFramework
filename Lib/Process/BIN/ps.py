import sys
import psutil
ps = psutil.Process(int(sys.argv[1]))
print(ps.status())
