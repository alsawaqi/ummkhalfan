import NProgress from 'nprogress';

const MIN_PROGRESS_DISPLAY_TIME = 1000; // 1 second, adjust as needed
let progressStartTime = 0;

function startProgress() {
  NProgress.start();
  progressStartTime = Date.now();
}

function endProgress() {
  const elapsedTime = Date.now() - progressStartTime;
  const remainingTime = MIN_PROGRESS_DISPLAY_TIME - elapsedTime;

  if (remainingTime > 0) {
    setTimeout(() => NProgress.done(), remainingTime);
  } else {
    NProgress.done();
  }
}
export { startProgress, endProgress };
