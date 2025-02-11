{
  containerDefinitions: [
    {
      entryPoint: ['bash', '.github/ecspresso/development/oneshot_db_migrate/entry_point.sh'],
      name: 'sqldef',
      image: '967125328503.dkr.ecr.ap-northeast-1.amazonaws.com/woa_laravel:' + std.extVar('TAG'),
      cpu: 0,
      interactive: true,
      pseudoTerminal: true,
      environment: [
        { name: 'DB_DATABASE', value: 'dev_woa' },
        { name: 'DB_HOST', value: 'dev-jinzaibank-db-cluster.cluster-c1jvfkkua8gb.ap-northeast-1.rds.amazonaws.com' },
        { name: 'DB_USERNAME', value: 'jinzaimaster' },
        { name: 'DB_MIGRATE_APPLY', value: std.extVar('DB_MIGRATE_APPLY') },
      ],
      secrets: [
        { name: 'DB_PASSWORD_ROOT', valueFrom: 'arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:DB_PASSWORD_ROOT::' },
      ],
      logConfiguration: {
        logDriver: 'awslogs',
        options: {
          'awslogs-create-group': 'true',
          'awslogs-group': '/ecs/woa/development/oneshot',
          'awslogs-region': 'ap-northeast-1',
          'awslogs-stream-prefix': 'db_migrate',
        },
      },
    },
  ],
  cpu: '1024',
  executionRoleArn: 'arn:aws:iam::967125328503:role/ecsTaskExecutionRole',
  family: 'development-woa-oneshot-sqldef',
  ipcMode: '',
  memory: '2048',
  networkMode: 'awsvpc',
  pidMode: '',
  requiresCompatibilities: [
    'FARGATE',
  ],
  runtimePlatform: {
    cpuArchitecture: 'X86_64',
    operatingSystemFamily: 'LINUX',
  },
  taskRoleArn: 'arn:aws:iam::967125328503:role/ecs_task_woa_webapp',
}
