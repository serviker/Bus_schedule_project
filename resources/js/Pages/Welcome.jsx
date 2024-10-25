// resources/js/Pages/Welcome.jsx
import '../../../public/css/welcome.css';
// import FindBusComponent from './BusSchedule/FindBusComponent';
// import BusScheduleComponent from './BusSchedule/BusScheduleComponent.jsx';

const Welcome = () => {
    return (
        <div className="welcome-container">
            <h1>Расписание автобусов!</h1>
            <div className="menu-container">
                <ul className="menu-list">
                    <li><a href="/api/show">View</a></li>
                    <li><a href="/api/find-bus">Search</a></li>
                    <li><a href="/add">Add</a></li>
                    <li><a href="/edit">Edit</a></li>
                    <li><a href="/delete">Delete</a></li>
                </ul>
            </div>
        </div>
    );
};

export default Welcome;

