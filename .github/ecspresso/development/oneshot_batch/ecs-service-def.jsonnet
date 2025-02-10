{
  deploymentConfiguration: {
    deploymentCircuitBreaker: {
      enable: true,
      rollback: true,
    },
    maximumPercent: 200,
    minimumHealthyPercent: 100,
  },
  deploymentController: {
    type: 'ECS',
  },
  enableECSManagedTags: true,
  enableExecuteCommand: false,
  launchType: 'FARGATE',
  networkConfiguration: {
    awsvpcConfiguration: {
      assignPublicIp: 'DISABLED',
      securityGroups: [
        'sg-00b2adfb81cf90cd9',
      ],
      subnets: [
        "subnet-0e3997d59892775dd",
        "subnet-07a87f3f74e649171"
      ],
    },
  },
  pendingCount: 0,
  platformFamily: 'Linux',
  platformVersion: 'LATEST',
  propagateTags: 'NONE',
  runningCount: 0,
  schedulingStrategy: 'REPLICA',
}