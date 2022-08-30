import axios from 'axios';
//eslint-disable-next-line
const logger = async (url: string, error:any) => {
    try {
        const formData = new FormData();
        formData.append('error', JSON.stringify(error));
        
        const request = await axios.post(`${url}logs/error`, formData, { headers: {"Content-type": "application/x-www-form-urlencoded"}});

        if(request.data.success) {
            console.log(`error logged`);
        }
    } catch (exception) {
        if(process.env.NODE_ENV === "development") console.error(exception);
    }
}

export default logger;