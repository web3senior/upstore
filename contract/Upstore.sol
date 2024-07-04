// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

import "@openzeppelin/contracts/utils/Counters.sol";
import "_ownable.sol";

/// @title Upstore 🆙
/// @author Aratta Labs
/// @notice Read the use cases
/// @dev Please ensure that you run the test before deploying. You can find the deployed contract addresses in the README.md file.
/// @custom:security-contact atenyun@gmail.com
contract Upstore is Ownable(msg.sender) {
    using Counters for Counters.Counter;
    Counters.Counter public _appCounter;
    uint256 public price;

    /// errors
    error Unauthorized();
    error PriceNotMet(uint256 price, uint256 amount);
    error DuplicatedAddress(bytes32 appId, address sender);

    // Events
    event appAdded(address indexed sender, bytes32 indexed appId, string metadata, address indexed manager, bool status);
    event likeAdded(address indexed sender, bytes32 indexed appId);
    event Log(string func, uint256 gas);

    struct AppStruct {
        bytes32 id;
        string metadata;
        address manager;
        uint256 dt;
        bool status;
    }

    AppStruct[] public app;

    struct LikeStruct {
        bytes32 appId;
        address sender;
        uint256 dt;
    }

    LikeStruct[] public like;

    mapping(bytes32 => mapping(bytes32 => string)) public blockStorage;

    ///@dev Throws if called by any account other than the manager.
    modifier onlyManager(bytes32 appId) {
        uint256 appIndex = _indexOfApp(appId);
        require(app[appIndex].manager == msg.sender, "The sender is not the manager of the entered app ID.");
        _;
    }

    constructor() {
        /// @dev Assert that count will start from 0
        assert(_appCounter.current() == 0);
        price = 1 ether;
    }

    // Function to receive Ether. msg.data must be empty
    receive() external payable {}

    // Fallback function is called when msg.data is not empty
    fallback() external payable {
        emit Log("fallback", gasleft());
    }

    /// @notice Update the price
    function setPrice(uint256 newPrice) public onlyOwner {
        price = newPrice;
    }

    /// @notice Store a new key/ value
    function setKey(
        bytes32 appId,
        bytes32 key,
        string memory val
    ) public onlyManager(appId) {
        blockStorage[appId][key] = val;
    }

    /// @notice Get the stored value
    /// @param appId The bytes32 ID
    /// @param key A byte32 key
    /// @return value in CID format
    function getKey(bytes32 appId, bytes32 key) public view returns (string memory) {
        return blockStorage[appId][key];
    }

    /// @notice Delete a key from the storage
    /// @param appId The bytes32 ID
    /// @param key A byte32 key
    /// @return boolean
    function delKey(bytes32 appId, bytes32 key) public onlyManager(appId) returns (bool) {
        delete blockStorage[appId][key];
        return true;
    }

    /// @notice Add a new dApp
    /// @dev If the manager field is left empty, the sender will be recognized as the manager
    /// @param metadata (CID)
    /// @return appId
    function setApp(string memory metadata, address manager) public payable returns (bytes32 appId) {
        /// @notice Continue if the sender is owner or msg.value met the price
        if (msg.sender != owner()) {
            if (msg.value < price) revert PriceNotMet(price, msg.value);
        }

        /// @notice Increase counter
        _appCounter.increment();

        /// @notice Store the new dApp fields
        app.push(AppStruct(bytes32(_appCounter.current()), metadata, manager == address(0) ? msg.sender : manager, block.timestamp, msg.sender == owner() ? true : false));

        /// @notice Emit that a new dApp has been added
        emit appAdded(msg.sender, bytes32(_appCounter.current()), metadata, manager == address(0) ? msg.sender : manager, msg.sender == owner() ? true : false);

        return bytes32(_appCounter.current());
    }

    /// @notice get app detail
    /// @param appId The unique bytes32 ID
    /// @return AppStruct
    function getApp(bytes32 appId) public view returns (AppStruct memory) {
        for (uint256 i = 0; i < app.length; i++) if (app[i].id == appId && app[i].status) return app[i];

        revert("The dAppId entered has not been declared or approved yet.");
    }

    /// @notice Like an app
    /// @dev A like per address
    /// @param appId The bytes32 ID
    /// @return bool
    function setLike(bytes32 appId) public returns (bool) {
        uint256 appIndex = _indexOfApp(appId);

        for (uint256 i = 0; i < like.length; i++) if (like[i].sender == msg.sender && like[i].appId == appId && msg.sender != owner()) revert DuplicatedAddress(appId, msg.sender);

        // Add new like
        like.push(LikeStruct(app[appIndex].id, msg.sender, block.timestamp));

        emit likeAdded(msg.sender, appId);

        return true;
    }

    /// @notice Get total like
    /// @param appId The bytes32 ID
    /// @return uint256
    function getLikeTotal(bytes32 appId) public view returns (uint256) {
        uint256 counter = 0;
        for (uint256 i = 0; i < like.length; i++) if (like[i].appId == appId) counter++;
        return counter;
    }

    // check if sender is the manager of the UPstore
    // returns boolean
    function updateMetadata(bytes32 appId, string memory metadata) public onlyManager(appId) returns (bool) {
        uint256 appIndex = _indexOfApp(appId);
        app[appIndex].metadata = metadata;
        return true;
    }

    /// @notice Display or hide the dApp from the store
    /// @dev Only the owner of this contract can call/ aprove the dApp
    /// @param appId The bytes32 ID
    /// @return boolean
    function updateApp(
        bytes32 appId,
        string memory metadata,
        address manager
    ) public onlyOwner returns (bool) {
        uint256 appIndex = _indexOfApp(appId);
        app[appIndex].metadata = metadata;
        app[appIndex].manager = manager;
        return true;
    }

    /// @notice  Number of apps currently added on the contract
    /// @return uint256
    function getAppTotal() public view returns (uint256) {
        return app.length;
    }

    /// @notice Verify if a address is on a manager
    /// @return appId
    function verifyManager(bytes32 appId, address manager) public view returns (AppStruct memory) {
        uint256 appIndex = _indexOfApp(appId);
        if (app[appIndex].manager == manager) return app[appIndex];

        revert("The manager address for the app ID you entered has not been determined.");
    }

    /// @notice Display or hide the dApp from the store
    /// @dev Only the owner of this contract can call/ aprove the dApp
    /// @param appId The bytes32 ID
    /// @return status
    function toggleStatus(bytes32 appId) public onlyOwner returns (bool status) {
        uint256 appIndex = _indexOfApp(appId);
        return app[appIndex].status = !app[appIndex].status;
    }

    /// @notice Retrive the App array
    /// @dev Returns the App array as AppStruct
    /// @return AppStruct
    /// @return total
    function getAppList() public view returns (AppStruct[] memory, uint256 total) {
        return (app, app.length);
    }

    /// @notice Retrive the App array
    /// @dev Like[] starts from 0 to its length, a way to fetch all and filter it
    /// @return total
    function getLikeTotal() public view returns (uint256 total) {
        return like.length;
    }

    /// @dev Retrieve the index of the app
    /// @param appId The bytes32 ID
    /// @return uint256
    function _indexOfApp(bytes32 appId) internal view returns (uint256) {
        for (uint256 i = 0; i < app.length; i++) if (app[i].id == appId) return i;
        revert("App Id Not Found");
    }

    // Function to withdraw all Ether from this contract.
    function withdraw() public onlyOwner {
        // get the amount of Ether stored in this contract
        uint256 amount = address(this).balance;

        // send all Ether to owner
        (bool success, ) = owner().call{value: amount}("");
        require(success, "Failed to send Ether");
    }

    // Function to transfer Ether from this contract to address from input
    function transfer(address payable _to, uint256 _amount) public onlyOwner {
        // Note that "to" is declared as payable
        (bool success, ) = _to.call{value: _amount}("");
        require(success, "Failed to send Ether");
    }

    /// @notice Get contract's balance
    function getBalance() public view onlyOwner returns (uint256) {
        return address(this).balance;
    }
}
