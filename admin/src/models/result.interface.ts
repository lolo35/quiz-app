export interface ResultInterface {
    success: boolean,
    passing: number,
    percent: number,
    points: number,
    total: number,
}

export interface RewardStateInterface {
    id: number,
    user_id: number,
    quiz_id: number,
    rewarded: boolean,
    created_at: string,
    updated_at: string,
}