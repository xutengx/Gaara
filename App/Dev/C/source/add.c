#include<stdio.h>

int main(int argc, char **argv) {
    //printf("参数个数：%d\n", argc-1);
    int a = atol(argv[1]);
    int b = atol(argv[2]);
    int sum = a + b;
    printf("%d\n", sum);
    return 0;
}