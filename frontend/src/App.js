import './App.css';
import {useState} from "react";
import {TagsInput} from "react-tag-input-component";
import uuid from 'react-uuid';
import axios from "axios";

const App = () => {
    const apiUrl = "http://build.test/api/";
    const dataTypes = ['clients', 'buildings', 'parkings'];

    const [selectedOption, setSelectedOption] = useState('clients');
    const [nameValue, setNameValue] = useState([]);
    const [idValue, setIdValue] = useState([]);
    const [clientId, setClientId] = useState([]);
    const [buildingIds, setBuildingIds] = useState([]);

    const params = {
        name: nameValue,
        client_id: clientId,
        building_ids: JSON.stringify(buildingIds)
    };

    const [responseData, setResponseData] = useState([]);
    const [responseError, setResponseError] = useState(null);

    const getApiData = (e) => {
        const targetAttribute = e.target.getAttribute("data-request-type");

        axios
            .get(apiUrl + targetAttribute)
            .then((response) => {
                setResponseData(JSON.stringify(response.data));
            })
            .catch(function (error) {
                setResponseError(error.code)
            })
    };

    const getApiDataById = (e) => {
        const targetAttribute = e.target.getAttribute("data-request-type");

        axios
            .get(apiUrl + targetAttribute + '/' + idValue)
            .then((response) => {
                setResponseData(JSON.stringify(response.data));
            })
            .catch(function (error) {
                setResponseError(error.code)
            })
    };

    const postApiData = (e) => {
        const targetAttribute = e.target.getAttribute("data-request-type");

        axios
            .post(apiUrl + targetAttribute, params)
            .then((response) => {
                setResponseData(JSON.stringify(response.data));
            })
            .catch(function (error) {
                setResponseError(error.code)
            })
    };

    const validateId = (string) => {
        let numTest = /^\d+$/.test(string);

        return numTest;
    }

    return (
        <div className="App">

            <h1>Buildings API test</h1>

            <div className="controls-wrapper">
                <h2>Get all data or data by type from DB.</h2>

                <div className="section-wrapper">
                    <button
                        key={uuid()}
                        onClick={getApiData}
                        data-request-type="everything"
                    >GET everything</button>

                    {dataTypes.map(type => (
                        <button
                            key={uuid()}
                            onClick={getApiData}
                            data-request-type={type}
                        >GET all {type}</button>
                    ))}
                </div>

                <br/>

                <h2>Get data from DB by entry ID.</h2>
                <div className="section-wrapper">

                    <input
                        type="number"
                        name="id"
                        placeholder="Entry ID"
                        min="1"
                        value={idValue}
                        onChange={(e) => {setIdValue(e.target.value)}}
                    />

                    {dataTypes.map(type => (
                        <button
                            disabled={!idValue.length}
                            key={uuid()}
                            onClick={getApiDataById}
                            data-request-type={type}
                        >GET {type.substring(0,type.length-1)}</button>
                    ))}
                </div>

                <br/>

                <h2>Write data to DB by type.</h2>
                <div className="section-wrapper">
                    <>
                        <select
                            onChange={(e) => {
                                setSelectedOption(e.target.value);
                            }}
                            value={selectedOption}>

                            {dataTypes.map(type => (
                                <option
                                    key={uuid()}
                                    value={type}
                                >{type}</option>
                            ))}
                        </select><br/>
                    </>
                    <>
                        <input
                            type="text"
                            name="name"
                            placeholder="Name"
                            value={nameValue}
                            onChange={(e) => {setNameValue(e.target.value)}}
                        /><br/>
                    </>

                    {(selectedOption !== 'clients' && selectedOption !== 'parkings') &&
                        <>
                            <input
                                type="number"
                                name="parent_id"
                                placeholder="Client (parent) ID"
                                min="1"
                                value={clientId}
                                onChange={(e) => {setClientId(e.target.value)}}
                            /><br/>
                        </>
                    }

                    {(selectedOption !== 'clients' && selectedOption !== 'buildings') &&
                        <>
                            <TagsInput
                                value={buildingIds}
                                onChange={setBuildingIds}
                                beforeAddValidate={validateId}
                                name="ids"
                                placeHolder="Building (parents) ID's, only numbers"
                            />
                            <br/>
                        </>
                    }
                    <button
                        disabled={!nameValue.length}
                        onClick={postApiData}
                        data-request-type={selectedOption}
                    >POST {selectedOption}</button>
                </div>
            </div>

            <h2>API response</h2>
            <div className="response-wrapper">
                {responseError &&
                    <pre>{setResponseError}</pre>
                }

                {!responseError &&
                    <pre>{responseData}</pre>
                }
            </div>

        </div>
    );

}

export default App;
